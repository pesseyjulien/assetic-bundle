<?php

declare(strict_types=1);

namespace Symfony\Bundle\AsseticBundle\Templating;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Templating\TemplateNameParser as BaseTemplateNameParser;
use Symfony\Component\Templating\TemplateReferenceInterface;

/**
 * TemplateNameParser converts template names from the short notation
 * "bundle:section:template.format.engine" to TemplateReferenceInterface
 * instances.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class TemplateNameParser extends BaseTemplateNameParser
{
    protected $kernel;
    protected $cache = [];

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * {@inheritdoc}
     */
    public function parse($name)
    {
        if ($name instanceof TemplateReferenceInterface) {
            return $name;
        } elseif (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        // normalize name
        $name = preg_replace('#/{2,}#', '/', str_replace('\\', '/', $name));

        if (str_contains($name, '..')) {
            throw new \RuntimeException(sprintf('Template name "%s" contains invalid characters.', $name));
        }

        if (!preg_match('/^(?:([^:]*):([^:]*):)?(.+)\.([^\.]+)\.([^\.]+)$/', $name, $matches)
            || !preg_match('/^(?:@(.+)\/(.+)\/)?(.+)\.([^\.]+)\.([^\.]+)$/', $name, $matches)) {
            return parent::parse($name);
        }

        $bundle = strrpos($matches[1], 'Bundle') === false ? $matches[1].'Bundle' : $matches[1];
        $template = new TemplateReference($bundle, $matches[2], $matches[3], $matches[4], $matches[5]);

        if ($template->get('bundle')) {
            try {
                $this->kernel->getBundle($template->get('bundle'));
            } catch (\Exception $e) {
                throw new \InvalidArgumentException(sprintf('Template name "%s" is not valid.', $name), 0, $e);
            }
        }

        return $this->cache[$name] = $template;
    }
}
