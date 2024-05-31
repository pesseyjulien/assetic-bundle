<?php

/*
 * This file is part of the Symfony framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\Bundle\AsseticBundle\Templating;

use Assetic\Asset\AssetInterface;
use Assetic\Factory\AssetFactory;
use Symfony\Component\Asset\Packages;

/**
 * The static "assetic" templating helper.
 *
 * @author Kris Wallsmith <kris@symfony.com>
 */
class StaticAsseticHelper extends AsseticHelper
{
    private $packages;

    /**
     * @param Packages     $packages The assets packages
     * @param AssetFactory $factory  The asset factory
     */
    public function __construct($packages, AssetFactory $factory)
    {
        $this->packages = $packages;

        parent::__construct($factory);
    }

    protected function getAssetUrl(AssetInterface $asset, $options = array())
    {
        $package = isset($options['package']) ? $options['package'] : null;

        return $this->packages->getPackage($package)->getUrl($asset->getTargetPath());
    }
}
