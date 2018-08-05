<?php

namespace Recca0120\Attest;

trait Permissible
{
    protected function permit($sources, $targets)
    {
        if (is_string($targets) === true) {
            if ($matched = $this->permitAnd($sources, $targets) !== false) {
                return $matched;
            }

            if ($matched = $this->permitOr($sources, $targets) !== false) {
                return $matched;
            }
        }

        return $this->permitAll(
            $sources,
            $this->getTargetSlug(is_array($targets) === true ? $targets : [$targets])
        );
    }

    protected function permitAnd($sources, $targets)
    {
        $pattern = '/(\sand\s|&&|&|,)/i';
        if ((bool) preg_match($pattern, $targets) === false) {
            return false;
        }

        return $this->permitAll($sources, preg_split($pattern, $targets));
    }

    protected function permitOr($sources, $targets)
    {
        $pattern = '/(\sor\s|\|\||\|)/i';
        if ((bool) preg_match($pattern, $targets) === false) {
            return false;
        }

        return $this->permitOne($sources, preg_split($pattern, $targets));
    }

    protected function permitOne($sources, $targets)
    {
        foreach ($targets as $target) {
            if ($this->permitExists($sources, $target) === true) {
                return true;
            }
        }

        return false;
    }

    protected function permitAll($sources, $targets)
    {
        foreach ($targets as $target) {
            if ($this->permitExists($sources, $target) === false) {
                return false;
            }
        }

        return true;
    }

    protected function getTargetSlug(array $targets)
    {
        return array_map(function ($target) {
            return is_object($target) ? $target->slug : $target;
        }, $targets);
    }

    private function permitExists($sources, $target)
    {
        return $sources->contains(function ($source) use ($target) {
            return $source->slug === $target;
        });
    }
}
