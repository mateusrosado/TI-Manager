var dom = document.getElementById('chart-container');
var myChart = echarts.init(dom, null, {
  renderer: 'canvas',
  useDirtyRect: false
});
var app = {};

var option;

const posList = [
  'left',
  'right',
  'top',
  'bottom',
  'inside',
  'insideTop',
  'insideLeft',
  'insideRight',
  'insideBottom',
  'insideTopLeft',
  'insideTopRight',
  'insideBottomLeft',
  'insideBottomRight'
];
app.configParameters = {
  rotate: {
    min: -90,
    max: 90
  },
  grid: {
    height: '100%',
    width: '100%',
    left: 0,
    bottom: 5
  },
  align: {
    options: {
      left: 'left',
      center: 'center',
      right: 'right'
    }
  },
  verticalAlign: {
    options: {
      top: 'top',
      middle: 'middle',
      bottom: 'bottom'
    }
  },
  position: {
    options: posList.reduce(function (map, pos) {
      map[pos] = pos;
      return map;
    }, {})
  },
  distance: {
    min: 0,
    max: 100
  }
};
app.config = {
  rotate: 90,
  align: 'left',
  verticalAlign: 'middle',
  position: 'insideBottom',
  distance: 15,
  onChange: function () {
    const labelOption = {
      rotate: app.config.rotate,
      align: app.config.align,
      verticalAlign: app.config.verticalAlign,
      position: app.config.position,
      distance: app.config.distance
    };
    myChart.setOption({
      series: [
        {
          label: labelOption
        },
        {
          label: labelOption
        },
        {
          label: labelOption
        },
        {
          label: labelOption
        }
      ]
    });
  }
};
const labelOption = {
  show: true,
  position: app.config.position,
  distance: app.config.distance,
  align: app.config.align,
  verticalAlign: app.config.verticalAlign,
  rotate: app.config.rotate,
  formatter: '{c}  {name|{a}}',
  fontSize: 16,
  rich: {
    name: {}
  }
};
option = {
  tooltip: {
    trigger: 'axis',
    axisPointer: {
      type: 'shadow'
    }
  },
  legend: {
    data: ['Entrada', 'Saída']
  },
  toolbox: {
    show: true,
    orient: 'vertical',
    left: 'right',
    top: 'center',
    feature: {
      mark: { show: true },
      dataView: { show: true, readOnly: false },
      magicType: { show: true, type: ['line', 'bar', 'stack'] },
      restore: { show: true },
      saveAsImage: { show: true }
    }
  },
  // grid: {
  //   containLabel: true,
  // },
  xAxis: [
    {
      type: 'category',
      axisTick: { show: false },
      // data: ['Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez', 'Jan', 'Fev', 'Mar']
      data: months
    }
  ],
  yAxis: [
    {
      type: 'value'
    }
  ],
  series: [
    {
      name: 'Entrada',
      type: 'bar',
      barGap: 0,
      label: labelOption,
      emphasis: {
        focus: 'series'
      },
      // data: [320, 332, 301, 334, 390, 100, 20, 0, 0, 50, 14, 100]
      data: entry
    },
    {
      name: 'Saída',
      type: 'bar',
      label: labelOption,
      emphasis: {
        focus: 'series'
      },
      // data: [220, 182, 191, 234, 290, 0, 0, 0, 0, 0, 0, 0, 0]
      data: exits
    },
  ],
  // width: '100%', // Defina a largura como 100% do contêiner pai
  // height: '100%' // Defina a altura como 100% do contêiner pai
};


if (option && typeof option === 'object') {
  myChart.setOption(option);
}

window.addEventListener('resize', myChart.resize);