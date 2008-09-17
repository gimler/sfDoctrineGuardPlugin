<?php

/*
 * This file is part of the symfony package.
 * (c) Gordon Franke <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Crawl active feeds.
 *
 * @package    symfony
 * @subpackage task
 * @author     Gordon Franke
 * @version    SVN: $Id: gfFeedAggregatorCrawlTask.class.php 8109 2008-03-27 10:40:33Z gimler $
 */
class gfFeedAggregatorCrawlTask extends sfDoctrineBaseTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name'),
    ));

    $this->addOptions(array(
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
    ));

    $this->namespace = 'feed-aggregator';
    $this->name = 'crawl';
    $this->briefDescription = 'Crawl all feeds';

    $this->detailedDescription = <<<EOF
The [feed-aggregator:crawl|INFO] task crawl all active feeds:

  [./symfony feed-aggregator:crawl|INFO]
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $configuration = ProjectConfiguration::getApplicationConfiguration($arguments['application'], $options['env'], true);

    $databaseManager = new sfDatabaseManager($configuration);

    $feeds = gfFeedAggregatorFeedTable::getAllActiveFeeds();
    $f = $e = 0;
    foreach($feeds as $feed)
    {
      try
      {
        //TODO: convert to utf-8(default charset of symfony)
        $rssFeed = sfFeedPeer::createFromWeb($feed['url']);

        // override old items
        $dbItems = array();
        if($feed['delete_old'])
        {
//          $this->log('delete old items');

          gfFeedAggregatorFeedItemTable::deleteAllFromFeed($feed['id']);
        }
        else
        {
//          $this->log('get items from db');

          $ids = array();
          foreach($rssFeed->getItems() as $item)
          {
            $ids[] = $item->getUniqueId();
          }
          $items = gfFeedAggregatorFeedItemTable::getFromFeedWithUniqueId($feed['id'], $ids);
          foreach($items as $item)
          {
            $dbItems[$item['unique_id']] = $item;
          }
        }

        $ii = $iu = $is = 0;
        foreach($rssFeed->getItems() as $item)
        {
          $unique_id = ($item->getUniqueId() == '') ? md5($item->getLink()):$item->getUniqueId();
          if(!isset($dbItems[$unique_id]))
          {
//            $this->log(sprintf('create %s', $unique_id));
            $dbItem = new gfFeedAggregatorFeedItem();
          }
          else if(!$feed['override'])
          {
//            $this->log(sprintf('skip %u', $unique_id));
            $is++;
            continue;
          }
          else
          {
//            $this->log(sprintf('override %u', $unique_id));
            $dbItem = $dbItems[$unique_id];
            $iu++;
          }
          //TODO: not working, stop timestampable
          if ($item->getPubdate()) var_dump($item->getPubdate());

          $dbItem->setCreatedAt($item->getPubdate());
          $dbItem->setUpdatedAt($item->getPubdate());

          // feed item columns
          $dbItem->setUniqueId($unique_id);
          $dbItem->setTitle($item->getTitle());
          $dbItem->setDescription($item->getDescription());
          $dbItem->setContent($item->getContent());
          $dbItem->setLink($item->getLink());

          $dbItem->setFeedId($feed['id']);
          $dbItem->save();

          $ii++;
        }

        // update feed
        $feed->setLastFetch(date('c'));
        $feed->save();

        $f++;

        $this->logSection('feed-aggregator', sprintf('Crawl feed "%s" import %u items', $feed['name'], $ii));
        $this->logSection('feed-aggregator', sprintf('%u updated and %u skipped', $iu, $is));
      }
      catch(Exception $e)
      {
        $this->logSection('feed-aggregator', $e->getMessage(), null, 'ERROR');
        $e++;
      }
    }

    $this->logSection('feed-aggregator', sprintf('Crawl finish fetched %u feeds %u failures', $f, $e));
  }
}
