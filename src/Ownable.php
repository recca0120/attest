<?php

namespace Recca0120\Attest;

trait Ownable
{
    protected function require($sources, $targets)
    {
        if (is_string($targets) === true) {
            if ($matched = $this->operatorAnd($sources, $targets) !== false) {
                return $matched;
            }

            if ($matched = $this->operatorOr($sources, $targets) !== false) {
                return $matched;
            }
        }

        return $this->requireAll(
            $sources,
            $this->getTargetName(is_array($targets) === true ? $targets : [$targets])
        );
    }

    protected function operatorAnd($sources, $targets)
    {
        $pattern = '/(\sand\s|&&|&|,)/i';
        if ((bool) preg_match($pattern, $targets) === false) {
            return false;
        }

        return $this->requireAll($sources, preg_split($pattern, $targets));
    }

    protected function operatorOr($sources, $targets)
    {
        $pattern = '/(\sor\s|\|\||\|)/i';
        if ((bool) preg_match($pattern, $targets) === false) {
            return false;
        }

        return $this->requireOne($sources, preg_split($pattern, $targets));
    }

    protected function requireOne($sources, $targets)
    {
        foreach ($targets as $target) {
            if ($sources->containsStrict('name', trim($target)) === true) {
                return true;
            }
        }

        return false;
    }

    protected function requireAll($sources, $targets)
    {
        foreach ($targets as $target) {
            if ($sources->containsStrict('name', trim($target)) === false) {
                return false;
            }
        }

        return true;
    }

    protected function getTargetName(array $targets)
    {
        return array_map(function ($target) {
            return is_object($target) ? $target->name : $target;
        }, $targets);
    }
}
