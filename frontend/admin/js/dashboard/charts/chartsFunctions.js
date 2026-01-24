export function loadPieChart(id, labels, values, title) {
  const data = [
    {
      labels,
      values,
      type: "pie",
      hole: 0.45,

      textinfo: "percent",
      textposition: "inside",

      hovertemplate:
        "<b>%{label}</b><br>$%{value}<br>%{percent}<extra></extra>",

      marker: {
        colors: [
          "#008F86",
          "#00AFA1",
          "#00CDBC",
          "#66E0D4",
          "#B3F0EA",
          "#E6FAF8",
        ],
      },
    },
  ];

  const layout = {
    title: {
      text: title,
      font: {
        size: 18,
        weight: 600,
      },
    },

    showlegend: true,
    legend: {
      orientation: "v",
      x: 1.05,
      y: 0.5,
    },

    margin: {
      t: 50,
      b: 20,
      l: 20,
      r: 140,
    },

    paper_bgcolor: "transparent",
    plot_bgcolor: "transparent",
  };

  const config = {
    responsive: true,
    displayModeBar: false,
  };

  Plotly.newPlot(id, data, layout, config);
}

export function loadHorizontalBarChart(id, xarray, yarray, title) {
  const data = [
    {
      x: xarray,
      y: yarray,
      type: "bar",
      orientation: "h",

      // styling
      marker: {
        color: "#00cdbc",
        borderRadius: 6,
      },

      hovertemplate: "%{y}<br><b>$%{x}</b><extra></extra>",
    },
  ];

  const layout = {
    title: {
      text: title,
      font: {
        size: 18,
        weight: 100,
      },
    },

    margin: {
      l: 140, // space for long names
      r: 30,
      t: 50,
      b: 40,
    },

    xaxis: {
      title: "Revenue in $USD",
      gridcolor: "#e5e7eb",
      zeroline: false,
    },

    yaxis: {
      automargin: true,
    },

    paper_bgcolor: "transparent",
    plot_bgcolor: "transparent",
  };

  const config = {
    responsive: true,
    displayModeBar: false, // removes plotly toolbar
  };

  Plotly.newPlot(id, data, layout, config);
}
