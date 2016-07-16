<?php
namespace Thunder33345\EnderPrizm;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\math\Vector3;
use pocketmine\Plugin;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\TextFormat;

class SpeedCheck implements Listener
{
	public $speedp, $speedtp;

	public function __construct(Main $main)
	{
		$this->getOwner = $main;
		$this->getOwner->getLogger()->info(TextFormat::GREEN . "Anti Speed Module Loaded!");
	}

	public function onPlayerJoin(PlayerJoinEvent $event)
	{
		$name = $event->getPlayer()->getName();
		$this->speedtp[$name] = 0;
		if (!isset($this->speedp[$name])) {
			$this->speedp[$name] = 0;
		}
	}

	public function onMove(PlayerMoveEvent $event)
	{
		$player = $event->getPlayer();
		$name = $player->getName();
		switch ($d = $this->XZDistanceSquared($event->getFrom(), $event->getTo())) {
			case $d <= 3;
				$this->speedtp[$name] = 0;
				break;
			case $d >= 6;
				$this->speedtp[$name] += 3;
				$player->sendMessage(TF::BOLD . TF::RED . "SPEED HACK DETECTED");
				break;
			case $d >= 5;
				$this->speedtp[$name] += 2;
				$player->sendMessage(TF::BOLD . TF::RED . "SPEED HACK DETECTED");
				break;
			case $d >= 4;
				$this->speedtp[$name]++;
				break;
		}
		if ($d > 5) {
			$this->speedp++;
		}

		if ($player->getInventory()->getItemInHand()->getId() == 280) {
			if ($d > 0) {
				$this->getOwner->getServer()->broadcastMessage('[Debug](SPEED)' . $d . "(" . $this->speedp[$name] . "|" . $this->speedtp[$name] . ")");
			}
		}

		if ($this->speedtp[$name] >= 5) {
			$ip = $player->getAddress();
			$uid = $player->getUniqueId();
			$this->getOwner->logToFile("SpeedHack.log", "[Detected] NAME: $name IP: $ip UID: $uid");
			$this->getOwner->getServer()->broadcastMessage(TextFormat::GREEN . prefix . " " . TextFormat::RED . $name . TextFormat::YELLOW . " have been kicked for suspecting speed hack");
			$this->speedtp[$name] = 0;
			//$event->getPlayer()->kick(TextFormat::RED."[EnderPrizm] You have been kicked for suspecting fly hack");
		}
		if ($this->speedp[$name] >= 3) {
			$ip = $player->getAddress();
			$uid = $player->getUniqueId();
			$this->getOwner->logToFile("SpeedHack.log", "[Detected] NAME: $name IP: $ip UID: $uid");
			$this->getOwner->getServer()->broadcastMessage(TextFormat::GREEN . prefix . " " . TextFormat::RED . $name . TextFormat::YELLOW . " have been kicked for suspecting speed hack");
			$this->speedp[$name] = 0;
			//$event->getPlayer()->kick(TextFormat::RED."[EnderPrizm] You have been kicked for suspecting fly hack");
		}

	}

	public static function XZDistanceSquared(Vector3 $v1, Vector3 $v2)
	{
		return round(($v1->x - $v2->x) ** 2 + ($v1->z - $v2->z) ** 2, 5);
	}
}