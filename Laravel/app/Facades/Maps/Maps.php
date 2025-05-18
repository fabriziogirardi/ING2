<?php

namespace App\Facades\Maps;

class Maps
{
    /**
     * Check if a given coordinates point resides inside a polygon
     */
    public function getPointInPolygon(float $lat, float $lng, array $polygon): bool
    {
        $polygon                 = $this->readPolygonFormat($polygon);
        $pointsCount             = count($polygon);
        $timesCrossingBoundaries = 0;

        foreach ($polygon as $point => $pointValue) {
            $firstPoint  = $pointValue;
            $secondPoint = $polygon[($point + 1) % $pointsCount];
            if ($this->getPointsIntersectionMovingEast($firstPoint, $secondPoint, $lat, $lng)) {
                $timesCrossingBoundaries++;
            }
        }

        return (bool) ($timesCrossingBoundaries % 2);
    }

    /**
     * Check if a given point resides in the range of an intersection line
     * drawn by a line connecting two points, while the point moves to the East (right)
     */
    private function getPointsIntersectionMovingEast(array $firstPoint, array $secondPoint, float $lat, float $lng): bool
    {
        if ($firstPoint['lat'] <= $secondPoint['lat']) {
            if ($this->checkOutOfRange($firstPoint, $secondPoint, $lat, $lng)) {
                return false;
            }

            if ($this->checkInRangeLng($firstPoint, $secondPoint, $lng)) {
                return true;
            }

            if ($this->checkLineSlop($firstPoint, $secondPoint, $lat, $lng)) {
                return true;
            }
        }

        return $this->getPointsIntersectionMovingEast($secondPoint, $firstPoint, $lat, $lng);
    }

    /**
     * Checks if a given LAT and LNG coordinate is out of range to intersect
     * a line drawn by connecting two other coordinates.
     *
     * It returns true when it is out of range for LAT or LNG position.
     */
    private function checkOutOfRange(array $firstPoint, array $secondPoint, float $lat, float $lng): bool
    {
        return $this->checkOutOfRangeLat($firstPoint, $secondPoint, $lat)
                || $this->checkOutOfRangeLng($firstPoint, $secondPoint, $lng);
    }

    /**
     * Checks if a given point has the LAT out of range to intersect
     * the line of the boundary connecting two polygon points.
     *
     * It returns true when the point LAT is lower than the first LAT point, or
     * higher than the second LAT point, meaning it can't intersect the line
     * while moving towards left.
     */
    private function checkOutOfRangeLat(array $firstPoint, array $secondPoint, float $lat): bool
    {
        return $lat <= $firstPoint['lat'] || $lat > $secondPoint['lat'];
    }

    /**
     * Checks if a given point has the LNG out of range to intersect
     * the line of the boundary connecting two polygon points
     *
     * It returns true when the point LNG is higher than both of the LNG from first and
     * second point, meaning it can't intersect the line because is out of current boundaries
     */
    private function checkOutOfRangeLng(array $firstPoint, array $secondPoint, float $lng): bool
    {
        return $lng >= $firstPoint['lng'] && $lng >= $secondPoint['lng'];
    }

    /**
     * Checks if a given point has the LNG in range to intersect
     * the line of the boundary connecting two polygon points
     *
     * It returns true when the if to the west from the line
     * meaning that if it moves towards east, it will cross the boundary line
     */
    private function checkInRangeLng(array $firstPoint, array $secondPoint, float $lng): bool
    {
        return $lng < $firstPoint['lng'] && $lng < $secondPoint['lng'];
    }

    private function checkLineSlop(array $firstPoint, array $secondPoint, float $lat, float $lng): bool
    {
        return (($lat - $firstPoint['lat']) / ($lng - $firstPoint['lng'])) > (($secondPoint['lat'] - $firstPoint['lat']) / ($secondPoint['lng'] - $firstPoint['lng']));
    }

    /**
     * Check if the polygon is in array format, if not parse it and convert it to array format.
     */
    private function readPolygonFormat(array $polygon): array
    {
        if (count($polygon) <= 1) {
            return [];
        }

        if (! is_array($polygon[0])) {
            $polygon = $this->loopPolygonArrayToParsePoints($polygon);
        }

        return $polygon;
    }

    /**
     * Loop the polygon array and parse each point in order to create
     * a new array with a correct format to calculate the intersections
     */
    public function loopPolygonArrayToParsePoints(array $polygon): array
    {
        foreach ($polygon as $point => $data) {
            $polygon[$point] = $this->parsePolygonPoint($data);
        }

        return $polygon;
    }

    /**
     * Parse the polygon coordinates and convert them from string
     * to an array in ['lat' => $lat, 'lng' => $lng] format
     */
    private function parsePolygonPoint(string $coordinates): array
    {
        $parsedCoordinates = explode(',', $coordinates);

        return ['lat' => $parsedCoordinates[0], 'lon' => $parsedCoordinates[1]];
    }
}
