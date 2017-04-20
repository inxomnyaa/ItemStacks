<?php

namespace xenialdan\ItemStacks;

use pocketmine\plugin\PluginBase;
use xenialdan\ItemStacks\task\StackTask;

class Loader extends PluginBase {

	public function onEnable() {
		$this->saveResource('config.yml');
		$this->getServer()->getScheduler()->scheduleDelayedRepeatingTask(new StackTask($this), $this->getConfig()->get('task-speed', 15), $this->getConfig()->get('task-speed', 15));
	}
}