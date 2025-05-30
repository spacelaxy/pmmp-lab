<?php

namespace Spacelaxy\PluginTop;

use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\world\sound\PopSound;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\math\Vector3;

class Main extends PluginBase implements Listener
{
  private array $blocks = [];

  /**
   * Called when the plugin is enabled
  */

  public function onEnable(): void {
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $this->getLogger()->info('Plugin successfully enabled.');

    // Define the blocks that can be randomly placed

    $this->blocks = [
      VanillaBlocks::GRASS(),
      VanillaBlocks::DIRT(),
      VanillaBlocks::STONE(),
      VanillaBlocks::SAND(),
    ];
  }

  /**
   * Triggered when a block is broken
   * - Sends a message to the player
   * - Plays a pop sound
   * - Cancels the block break (block will not be destroyed)
  */

  public function onBlockBreak(BlockBreakEvent $event): void {
    $player = $event->getPlayer();
    $world = $player->getWorld();
    $position = $player->getPosition();

    $player->sendMessage("§aYou broke a block!");

    $world->addSound($position, new PopSound());
    $event->cancel();
  }

  /**
   * Triggered when a player joins the server
   * - Displays a welcome title
  */

  public function onPlayerJoin(PlayerJoinEvent $event): void {
    $player = $event->getPlayer();

    $player->sendTitle(
      '§l§aWelcome to Spacelaxy',
      '§l§bExplore and have fun!',
      20, // fade in duration (ticks)
      60, // stay duration (ticks)
      20  // fade out duration (ticks)
    );
  }

  /**
   * Triggered when a player moves
   * - Replaces the block under the player with a random predefined block
  */

  public function onPlayerMove(PlayerMoveEvent $event): void {
    $player = $event->getPlayer();
    $world = $player->getWorld();
    $pos = $player->getPosition();

    $x = (int) floor($pos->x);
    $y = (int) floor($pos->y) - 1; // target block directly beneath the player
    $z = (int) floor($pos->z);

    $blockPos = new Vector3($x, $y, $z);
    $randomBlock = $this->blocks[array_rand($this->blocks)];

    $world->setBlock($blockPos, $randomBlock);
  }
}