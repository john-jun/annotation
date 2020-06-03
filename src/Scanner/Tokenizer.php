<?php
declare(strict_types=1);
namespace Air\Annotation\Scanner;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Parser;
use PhpParser\ParserFactory;

/**
 * Class Tokenizer
 * @package Air\Annotation
 */
final class Tokenizer
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * Tokenizer constructor.
     */
    public function __construct()
    {
        $this->parser = (new ParserFactory())->create(ParserFactory::ONLY_PHP7);
    }

    /**
     * @param string $code
     * @return ClassName|null
     */
    public function parseClass(string $code): ?ClassName
    {
        foreach ($this->parser->parse($code) as $stmt) {
            if ($stmt instanceof Namespace_ && $stmt->name) {
                foreach ($stmt->stmts as $node) {
                    $namespace = $stmt->name->toString();

                    switch (true) {
                        case $node instanceof Class_:
                            $parseClass = new ClassName(
                                $namespace . '\\' . $node->name,
                                $node->isAbstract() ? 'abstract' : 'class'
                            );
                            break;

                        case $node instanceof Trait_:
                            $parseClass = new ClassName(
                                $namespace . '\\' . $node->name,
                                'trait'
                            );
                            break;

                        case $node instanceof Interface_:
                            $parseClass = new ClassName(
                                $namespace . '\\' . $node->name,
                                'interface'
                            );
                            break;
                    }
                }
            }
        }

        return $parseClass ?? null;
    }
}
