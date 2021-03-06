<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/15/17
 * Time: 8:42 AM
 */

namespace DataSourceBundle\Aws\EC2\Measurement\EBS;

use DataSourceBundle\Entity\Aws\EBS\Attachment\Attachment;
use DataSourceBundle\Entity\Aws\EBS\Volume\Volume;
use DataSourceBundle\Entity\Aws\EC2\Instance\Instance;
use DataSourceBundle\Entity\Aws\Tag\Tag;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Entity\Metric\Builder;

/**
 * Class Volumes
 * @package DataSourceBundle\Aws\EC2\Measurement\EBS
 */
class Size extends MeasurementAbstract
{
    /**
     * @return MetricCollection
     */
    public function getMetrics(): MetricCollection
    {
        $buildData = [];
        $volumes = $this->getVolumes();

        /**
         * @var $volume Volume
         */
        foreach ($volumes as $volume) {
            $key = $this->getRegion()->getCode()
                . $volume->getState();

            $instanceName = "";
            foreach ($volume->getAttachments() as $attachment) {
                $instanceName = $this->getInstanceTagName($attachment->getInstance());
                $key .= $instanceName;
            }

            if ($buildData[$key]) {
                $buildData[$key]['points'][0]['value'] += $volume->getSize();
                continue;
            }

            $buildData[$key] = [
                'name' => $this->getName(),
                'tags' => $this->getMeasurementTags($volume, $instanceName),
                'points' => [
                    [
                        'value' => $volume->getSize(),
                        'time' => $this->getMetricsDatetime(),
                        'unit' => 'GB',
                    ]
                ]
            ];
        }
        return Builder::build(array_values($buildData));
    }

    public function getNameParts(): array
    {
        return array_merge(
            parent::getNameParts(),
            ['size']
        );
    }
}
