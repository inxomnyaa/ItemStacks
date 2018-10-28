<?php
declare(strict_types = 1);

namespace xenialdan\ItemStacks\task;

use pocketmine\entity\object\ItemEntity;
use pocketmine\scheduler\Task;
use xenialdan\ItemStacks\Loader;

class StackTask extends Task{
	/** @var Loader $plugin */
	private $plugin;

	public function __construct(Loader $owner){
		$this->plugin = $owner;
	}

	public function onRun(int $currentTick){
		foreach ($this->plugin->getServer()->getLevels() as $level){
			foreach ($level->getEntities() as $entity){
				if (!$entity instanceof ItemEntity || $entity->isClosed()) continue;
				if ($entity->getItem()->getCount() >= $entity->getItem()->getMaxStackSize()) continue;
				if (empty($entities = $level->getNearbyEntities($entity->getBoundingBox()->expandedCopy(1, 1, 1), $entity))) continue;
				else{
					foreach ($entities as $possibleItem){
						if (!$possibleItem instanceof ItemEntity || $possibleItem->isClosed()) continue;
						if ($possibleItem->getItem()->getCount() >= $possibleItem->getItem()->getMaxStackSize()) continue;
						if ($entity->getItem()->equals($possibleItem->getItem(), true, true)){
							if (($newCount = $entity->getItem()->getCount() + $possibleItem->getItem()->getCount()) >= $entity->getItem()->getMaxStackSize()) continue;
							//stack
							$this->plugin->getLogger()->debug('Stacked ' . $entity->getItem() . ' with ' . $possibleItem->getItem());
							$entity->getItem()->setCount($newCount);
							$this->plugin->getLogger()->debug('got item ' . $entity->getItem());
							$possibleItem->close();
						}
					}
				}
			}
		}
	}

	public function cancel(){
		$this->getHandler()->cancel();
	}
}
