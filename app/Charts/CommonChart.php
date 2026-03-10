<?php

namespace App\Charts;

class CommonChart
{
    protected $labels = [];
    protected $datasets = [];
    protected $options = [];
    protected $id;

    public function __construct()
    {
        $this->id = 'chart_' . bin2hex(random_bytes(4));
    }

    public function labels(array $labels)
    {
        $this->labels = $labels;
        return $this;
    }

    public function options(array $options)
    {
        $this->options = $options;
        return $this;
    }

    public function dataset(string $name, string $type, array $data)
    {
        $this->datasets[] = [
            'name' => $name,
            'type' => $type,
            'data' => $data,
        ];
        return $this;
    }

    public function container()
    {
        return '<div id="' . e($this->id) . '" style="width:100%; height:350px;"></div>';
    }

    public function script()
    {
        $categories = json_encode($this->labels);
        $yTitle = $this->options['yAxis']['title']['text'] ?? '';
        $legend = $this->options['legend'] ?? [];

        $series = [];
        foreach ($this->datasets as $ds) {
            $series[] = [
                'name' => $ds['name'],
                'type' => $ds['type'],
                'data' => $ds['data'],
            ];
        }
        $seriesJson = json_encode($series);
        $legendJson = json_encode((object) $legend);

        return <<<JS
<script>
Highcharts.chart('{$this->id}', {
    xAxis: { categories: {$categories} },
    yAxis: { title: { text: '{$yTitle}' } },
    legend: {$legendJson},
    series: {$seriesJson},
    credits: { enabled: false }
});
</script>
JS;
    }
}
