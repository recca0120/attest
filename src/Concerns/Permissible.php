<?php

namespace Recca0120\Attest\Concerns;

trait Permissible
{
    /**
     * @param $sources
     * @param $targets
     * @return bool
     */
    protected function permit($sources, $targets)
    {
        if (
            is_string($targets) === true &&
            (
                $this->permitAnd($sources, $targets) === true ||
                $this->permitOr($sources, $targets) === true
            )
        ) {
            return true;
        }

        return $this->permitAll(
            $sources,
            $this->getPermissibleName(is_array($targets) === true ? $targets : [$targets])
        );
    }

    /**
     * @param $sources
     * @param $targets
     * @return bool
     */
    protected function permitAnd($sources, $targets)
    {
        $pattern = '/(\sand\s|&&|&|,)/i';
        if ((bool) preg_match($pattern, $targets) === false) {
            return false;
        }

        return $this->permitAll($sources, preg_split($pattern, $targets));
    }

    /**
     * @param $sources
     * @param $targets
     * @return bool
     */
    protected function permitOr($sources, $targets)
    {
        $pattern = '/(\sor\s|\|\||\|)/i';
        if ((bool) preg_match($pattern, $targets) === false) {
            return false;
        }

        return $this->permitOne($sources, preg_split($pattern, $targets));
    }

    /**
     * @param $sources
     * @param $targets
     * @return bool
     */
    protected function permitOne($sources, $targets)
    {
        $slugSources = $sources->pluck('slug')->toArray();
        foreach ($targets as $target) {
            if (in_array(trim($target), $slugSources) === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $sources
     * @param $targets
     * @return bool
     */
    protected function permitAll($sources, $targets)
    {
        $slugSources = $sources->pluck('slug')->toArray();
        foreach ($targets as $target) {
            if (in_array(trim($target), $slugSources) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $targets
     * @return array
     */
    protected function getPermissibleName(array $targets)
    {
        return array_map(function ($target) {
            return is_object($target) ? $target->slug : $target;
        }, $targets);
    }
}
