<?php
/** Created By Thunder33345 **/
namespace Thunder33345\EnderPrizm\Modules;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use Thunder33345\EnderPrizm\Loader;

class AntiReach implements Listener
{
	private $loader, $range;

	public function __construct(Loader $loader)
	{
		$this->loader = $loader;
		$this->range = $loader->getYml()['Max-reach'];
		$this->loader->getLogger()->info('Anti Reach Have Been Enabled ! Range Have Been Set To "' . $this->range . '"');
	}

	public function onPVP(EntityDamageEvent $ev)
	{
		if ($ev instanceof EntityDamageByEntityEvent) {
			if (!$ev->isCancelled()) {
				$attacker = $ev->getDamager();
				if ($attacker instanceof Player) {
					$victim = $ev->getEntity();
					$dist = $attacker->distance($victim->getPosition());
					if ($dist > $this->range AND $attacker->getLevel()->getName() == $victim->getLevel()->getName()) {
						if ($attacker->getInventory()->getItemInHand()->getId() == \pocketmine\item\Item::BOW) {
							return;
						} else {
							$ev->setCancelled(true);
							$this->loader->logToFile('logs.log', "[AntiReach] " . 'Player: "' . $attacker->getName() . '" was suspected using reach');
						}
					}
				}
			}
		}
	}
}