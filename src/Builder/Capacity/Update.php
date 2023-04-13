<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Prestashop\Builder\Capacity;

use PhpParser\Builder;
use PhpParser\Node;

final class Update implements Builder
{
    private null|Node\Expr|Node\Identifier $endpoint = null;
    private null|Node\Expr $data = null;

    public function __construct(public array $options = [])
    {
    }

    public function withEndpoint(Node\Expr|Node\Identifier $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function withData(Node\Expr $line): self
    {
        $this->data = $line;

        return $this;
    }

    public function getNode(): Node
    {
        if (null === $this->endpoint) {
            throw new \Exception(message: 'Please check your capacity builder, you should have selected an endpoint.');
        }
        if (null === $this->data) {
            throw new \Exception(message: 'Please check your capacity builder, you should have provided some data.');
        }

        $options = [];
        foreach ($this->options as $key => $value) {
            $options[] = new Node\Expr\ArrayItem(
                value: new Node\Scalar\String_($value),
                key: new Node\Scalar\String_($key)
            );
        }

        return new Node\Stmt\While_(
            cond: new Node\Expr\ConstFetch(new Node\Name('true')),
            stmts: [
                new Node\Stmt\TryCatch(
                    stmts: [
                        new Node\Stmt\Expression(
                            expr: new Node\Expr\MethodCall(
                                new Node\Expr\MethodCall(
                                    var: new Node\Expr\PropertyFetch(
                                        var: new Node\Expr\Variable('this'),
                                        name: new Node\Identifier('client'),
                                    ),
                                    name: $this->endpoint,
                                ),
                                new Node\Identifier('update'),
                                [
                                    new Node\Arg(value: $this->data),
                                    new Node\Arg(new Node\Expr\Array_($options)),
                                ],
                            ),
                        ),
                        new Node\Stmt\Expression(
                            expr: new Node\Expr\Assign(
                                var: new Node\Expr\Variable('line'),
                                expr: new Node\Expr\Yield_(
                                    value: new Node\Expr\New_(
                                        class: new Node\Name\FullyQualified(name: \Kiboko\Component\Bucket\AcceptanceResultBucket::class),
                                        args: [
                                            new Node\Arg(
                                                value: new Node\Expr\Variable('line'),
                                            ),
                                        ],
                                    ),
                                ),
                            ),
                        ),
                    ],
                    catches: [
                        new Node\Stmt\Catch_(
                            types: [
                                new Node\Name\FullyQualified('Exception'),
                            ],
                            var: new Node\Expr\Variable('exception'),
                            stmts: [
                                new Node\Stmt\Expression(
                                    expr: new Node\Expr\MethodCall(
                                        var: new Node\Expr\PropertyFetch(
                                            var: new Node\Expr\Variable('this'),
                                            name: 'logger',
                                        ),
                                        name: new Node\Identifier('error'),
                                        args: [
                                            new Node\Arg(
                                                value: new Node\Expr\MethodCall(
                                                    var: new Node\Expr\Variable('exception'),
                                                    name: new Node\Identifier('getMessage'),
                                                ),
                                            ),
                                            new Node\Arg(
                                                value: new Node\Expr\Array_(
                                                    items: [
                                                        new Node\Expr\ArrayItem(
                                                            value: new Node\Expr\Variable('exception'),
                                                            key: new Node\Scalar\String_('exception'),
                                                        ),
                                                        new Node\Expr\ArrayItem(
                                                            value: new Node\Expr\Variable('line'),
                                                            key: new Node\Scalar\String_('item'),
                                                        ),
                                                    ],
                                                    attributes: [
                                                        'kind' => Node\Expr\Array_::KIND_SHORT,
                                                    ],
                                                ),
                                            ),
                                        ],
                                    ),
                                ),
                                new Node\Stmt\Expression(
                                    expr: new Node\Expr\Assign(
                                        var: new Node\Expr\Variable('line'),
                                        expr: new Node\Expr\Yield_(
                                            value: new Node\Expr\New_(
                                                class: new Node\Name\FullyQualified(
                                                    name: \Kiboko\Component\Bucket\RejectionResultBucket::class
                                                ),
                                                args: [
                                                    new Node\Arg(
                                                        value: new Node\Expr\Variable('exception'),
                                                    ),
                                                    new Node\Arg(
                                                        value: new Node\Expr\Variable('line'),
                                                    ),
                                                ],
                                            ),
                                        ),
                                    ),
                                ),
                            ],
                        ),
                    ],
                ),
            ],
        );
    }
}
