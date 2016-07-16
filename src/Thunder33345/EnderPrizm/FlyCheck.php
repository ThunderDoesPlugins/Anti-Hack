<?php
namespace Thunder33345\EnderPrizm;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\Plugin;
use pocketmine\utils\TextFormat as tf;
use pocketmine\utils\TextFormat;

class FlyCheck implements Listener
{
	public $playerstfp = [];
	public $playersfp = [];
	private $getOwner;

	public function __construct(Main $main)
	{
		$this->getOwner = $main;
		$this->getOwner->getLogger()->info(TextFormat::GREEN . "Anti Fly Module Loaded!");
	}

	public function onPlayerJoin(PlayerJoinEvent $event)
	{
		$name = $event->getPlayer()->getName();
		$this->playerstfp[$name] = 0;
		if (!isset($this->playersfp[$name])) {
			$this->playersfp[$name] = 0;
		}
	}

	public function PlayerMove(PlayerMoveEvent $event)
	{
		$player = $event->getPlayer();
		$name = $player->getName();
		$r = round($event->getTo()->getY() - $event->getFrom()->getY(), 3);

		if($player->getInventory()->getItemInHand()->getId() == 280) {
			if ($r > 0) {
				$this->getOwner->getServer()->broadcastMessage('[Debug](FLY)' . $r);
			}
		}

		$ip = $player->getAddress();
		$uid = $player->getUniqueId();
		switch ($r) {
			case'0.333';
			case'0.431';
			case'0.348';
				$this->playerstfp[$name] = 0;
				break;
			case'0';
				break;
			case"0.375";
			case"0.398";
			case"0.389";
			case"0.373";
			case $r >= 0.345 AND $r <= 0.400;
				$this->playerstfp[$name]++;
				$this->playersfp[$name]++;
				$player->sendMessage(TF::BOLD . TF::RED . "FLY HACK DETECTED");
				$this->getOwner->logToFile("FlyHack.log","[Suspected] NAME: $name IP: $ip UID: $uid");
				break;
			default;
				$this->playerstfp[$name] = '0';
				break;
		}
		if ($this->playerstfp[$name] >= 3) {
			$this->getOwner->logToFile("FlyHack.log","[Detected] NAME: $name IP: $ip UID: $uid");
			$this->getOwner->getServer()->broadcastMessage(TextFormat::GREEN . prefix . " " . TextFormat::RED . $name . TextFormat::YELLOW . " have been kicked for suspecting fly hack");
			$this->playerstfp[$name] = 0;
			//$event->getPlayer()->kick(TextFormat::RED."[EnderPrizm] You have been kicked for suspecting fly hack");
		}
		if ($this->playersfp[$name] >= 15) {
			$this->getOwner->logToFile("FlyHack.log","[Detected] NAME: $name IP: $ip UID: $uid");
			$this->getOwner->getServer()->broadcastMessage(TextFormat::GREEN . prefix . " " . TextFormat::RED . $name . TextFormat::YELLOW . " have been kicked for suspecting fly hack");
			$this->playersfp[$name] = 0;
			//$event->getPlayer()->kick(TextFormat::RED."[EnderPrizm] You have been kicked for suspecting fly hack");
		}

	}
}