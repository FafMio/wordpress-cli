<?php

namespace App\Service;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use function Symfony\Component\String\u;

class BlockGeneratorService
{

    private string $blockName;
    private string $blockTitle;
    private string $dashicon;
    private array $blockStrings = [];

    public function __construct(
        private ContainerBagInterface $containerBag,
    )
    {
    }

    public function generate(string $blockName, string $blockTitle, string $dashicon): void
    {
        $this->blockName = $blockName;
        $this->blockTitle = $blockTitle;
        $this->dashicon = $dashicon;

        $this->blockStrings = [
            "template" => [
                "file" => u("block " . $this->blockName)->camel(),
                "extension" => ".html.twig",
                "path" => "/templates/partials/acf/blocks/",
            ],
            "style" => [
                "file" => u($this->blockName . " Block")->camel(),
                "extension" => ".scss",
                "path" => "/assets/styles/blocks/",
            ],
            "php" => [
                "file" => "block" . u($this->blockName)->camel()->title(),
                "extension" => ".php",
                "path" => "/blocks/",
            ],
        ];

        $this->generateTemplate();
        $this->generateStyle();
        $this->generatePhpBlock();
        $this->registerACFBlock();
    }

    private function generateTemplate(): void
    {
        $src = $this->blockStrings['template'];
        $fileDest = $this->containerBag->get('wp_theme_dir') . $src['path'] . $src['file'] . $src['extension'];

        // Create file
        $myfile = fopen($fileDest, "w") or die("Unable to open file!");
        $txt = file_get_contents( __DIR__ . "/../../maker/block/template.html.twig");
        fwrite($myfile, $txt);
        fclose($myfile);

        FileService::fileReplaceContent($fileDest, "/-/referrer/-/", $this->blockStrings['style']['file']);
    }

    private function generateStyle(): void
    {
        $src = $this->blockStrings['style'];
        $fileDest = $this->containerBag->get('wp_theme_dir') . $src['path'] . $src['file'] . $src['extension'];

        // Create file
        $myfile = fopen($fileDest, "w") or die("Unable to open file!");
        $txt = file_get_contents( __DIR__ . "/../../maker/block/style.scss");
        fwrite($myfile, $txt);
        fclose($myfile);

        FileService::appendFile($this->containerBag->get('wp_theme_dir') . "/assets/styles/index.scss", "@import 'blocks/" . $src['file'] . "';");

        FileService::fileReplaceContent($fileDest, "/-/referrer/-/", $src['file']);
    }

    private function generatePhpBlock(): void
    {
        $src = $this->blockStrings['php'];
        $fileDest = $this->containerBag->get('wp_theme_dir') . $src['path'] . $src['file'] . $src['extension'];

        // Create file
        $myfile = fopen($fileDest, "w") or die("Unable to open file!");
        $txt = file_get_contents( __DIR__ . "/../../maker/block/block.php");
        fwrite($myfile, $txt);
        fclose($myfile);

        FileService::fileReplaceContent($fileDest, "/-/referrer/-/", $this->blockStrings['template']['file']);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function registerACFBlock(): void
    {
        $bridgePath = $this->containerBag->get('wp_theme_dir') . "/src/Bridge/BlockBridge.php";
        $src = $this->blockStrings;
        $newACFBlockString = "
        acf_register_block_type( array(
			'name'            => '" . $src['php']['file'] . "',
			'title'           => __( ' " . $this->blockTitle . " ' ),
			'description'     => __( ' ' ),
			'render_template' => '" . $src['php']['path'] . $src['php']['file'] . $src['php']['extension'] . "',
			'category'        => 'inserr_blocks',
			'icon'            => '" . $this->dashicon . "',
			'mode'            => 'edit',
			'keywords'        => [ '', ],
		) );
        ";

        $all = file_get_contents($bridgePath);
        $find = strpos($all, "//-/DO NOT REMOVE THIS IS MADE FOR WP-CLI/-/");
        $write = substr($all, 0, $find) . "\n " . $newACFBlockString . "\n" . substr($all, $find);
        file_put_contents($bridgePath, $write);
    }
}