<?php

namespace ComposerRequireChecker\ASTLocator;

use PhpParser\ErrorHandler;
use PhpParser\Parser;
use Traversable;

final class LocateASTFromFiles
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var ErrorHandler|null
     */
    private $errorHandler;

    public function __construct(Parser $parser, ?ErrorHandler $errorHandler)
    {
        $this->parser = $parser;
        $this->errorHandler = $errorHandler;
    }

    /**
     * @param Traversable|string[] $files
     *
     * @return Traversable|array[] a series of AST roots, one for each given file
     */
    public function __invoke(Traversable $files): Traversable
    {
        foreach ($files as $file) {
            $fileContent = file_get_contents($file);
            if ($fileContent === false) {
                throw new \InvalidArgumentException('could not load file [' . $file . ']');
            }
            yield $this->parser->parse($fileContent, $this->errorHandler);
        }
    }
}
