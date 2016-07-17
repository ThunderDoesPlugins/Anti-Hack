<?php
/** Created By Thunder33345 **/
namespace Thunder33345\EnderPrizm\Modules;

use pocketmine\scheduler\PluginTask;
use Thunder33345\EnderPrizm\Loader;

class ForceOPTask extends PluginTask
{
	public $loader;

	public function __construct(Loader $owner)
	{
		parent::__construct($owner);
		$this->loader = $owner;
		$this->loader->getLogger()->info('Anti Force OP Module Enabled');
	}

	public function onRun($currentTick)
	{
		foreach ($this->owner->getServer()->getOnlinePlayers() as $p) {
			if ($p->isOp() AND !$p->hasPermission('enderprizm.bypass.op')) {
				$this->owner->getServer()->removeOp($p->getName());
				$this->loader->logToFile("logs.log", '[ForceOP]' . 'Player: ' . $p->getName() . ' has been deoped due to insufficient permission');
			}
		}
	}
}