<!doctype html>
<html>

<head>
  <meta charset='utf-8'>
  <title>Pdp online solver</title>

  <script src="js/common/jquery-1.9.0-min.js"></script>
  <script src="js/common/jquery-ui-1.9.2.min.js"></script>
  <link rel="stylesheet" href="css/jquery-ui/jquery-ui.css">

  <!-- Sidebar dependencies -->
  <link rel="stylesheet" href="css/sidebar/lib/fontello.css" />
  <link rel="stylesheet" href="css/sidebar/lib/normalize.css" />
  <link rel="stylesheet" href="css/sidebar/sidebar.css" />
  <!-- end Sidebar dependencies -->

  <script src="js/handsontable.js"></script>
  <link rel="stylesheet" href="css/handsontable.css">

  <!-- jsPlumb dependencies -->
  <link rel="stylesheet" href="css/jsplumb/jsplumb.css">
  <!-- JS -->
  <script src="js/common/jquery-1.9.0-min.js"></script>
  <script src="js/common/jquery-ui-1.9.2.min.js"></script>
  <script src="js/jsplumb/external/jquery.ui.touch-punch-0.2.2.min.js"></script>
  <!-- support lib for bezier stuff -->
  <script src="js/jsplumb/lib/jsBezier-0.6.js"></script>
  <!-- event adapter -->
  <script src="js/jsplumb/lib/mottle-0.4.js"></script>
  <!-- geom functions -->
  <script src="js/jsplumb/lib/biltong-0.2.js"></script>
  <!-- jsplumb util -->
  <script src="js/jsplumb/src/util.js"></script>
  <script src="js/jsplumb/src/browser-util.js"></script>
  <!-- base DOM adapter -->
  <script src="js/jsplumb/src/dom-adapter.js"></script>
  <!-- main jsplumb engine -->
  <script src="js/jsplumb/src/jsPlumb.js"></script>
  <!-- endpoint -->
  <script src="js/jsplumb/src/endpoint.js"></script>
  <!-- connection -->
  <script src="js/jsplumb/src/connection.js"></script>
  <!-- anchors -->
  <script src="js/jsplumb/src/anchors.js"></script>
  <!-- connectors, endpoint and overlays  -->
  <script src="js/jsplumb/src/defaults.js"></script>
  <!-- bezier connectors -->
  <script src="js/jsplumb/src/connectors-bezier.js"></script>
  <!-- state machine connectors -->
  <script src="js/jsplumb/src/connectors-statemachine.js"></script>
  <!-- flowchart connectors -->
  <script src="js/jsplumb/src/connector-editors.js"></script>
  <!-- SVG renderer -->
  <script src="js/jsplumb/src/renderers-svg.js"></script>
  <!-- vml renderer -->
  <script src="js/jsplumb/src/renderers-vml.js"></script>
  <!-- common adapter -->
  <script src="js/jsplumb/src/base-library-adapter.js"></script>
  <!-- jquery jsPlumb adapter -->
  <script src="js/jsplumb/src/jquery.jsPlumb.js"></script>
  <!-- /JS -->
  <!-- end of jsPlumb dependencies -->

  <link rel="stylesheet" media="screen" href="css/main.css">

</head>

<body>

  <script>
    // GLOBAL DEF
    _pairCount = 10;

    _default_depot_coords = {
      x: 200,
      y: 200
    };

    _default_check_transitional_loading_probability = 20;
    _default_weight_capacity = 1000;
    _default_load_area = { x: 100, y: 100, z: 100 };
    _default_precise = 5;

    _default_vehicle_count = 3;
    // _default_python_file = "/home/vagrant/code/pdp-php/demo/pdphelper/pdphelper.py";
    _default_python_file = "<?=realpath(__DIR__ . '/../pdphelper/pdphelper.py') ?>";
  </script>

  <div class="jsc-sidebar-content jsc-sidebar-pulled sidebar" style="z-index:999">
    <nav>
      <a href="#" class="icon-menu link-menu jsc-sidebar-trigger"></a>
      <b style="font-size:20px" class="inline">Welcome to online PDP solver!</b>
    </nav>
    <section class="container">
      <div id="header_container">
        <!-- <input type="file" id="pdp_points_filename" /> -->

        <!-- <button name="load" id="load">Load</button>
            <button name="save" id="save">Save</button> -->
        <button id="export_points_button">Export points</button>
        <button id="clusterize_button">Clusterize</button>
        <button id="reset_clusters_button">Reset clusters</button>

        <button id="solve_gen_all_button">Solve all clusters</button>
        <button id="draw_all_paths_button">Draw all paths</button>
        <button id="draw_all_paths_step_by_step_button">Draw all paths step by step</button>
        <button id="clean_all_connections_button">Clean all connections</button>

        <!-- <button name="solve_branch_bound" class="solve_button" id="solve_branch_bound">Solve using branch and bound</button> -->
        <div id="general_console">

        </div>
      </div>
      <div class="demo points_container" id="points_container">
        <div class="shape pdp_point depot" data-shape="Circle" title="depot" data-point-id="0" id="pdp_point_0">D</div>
        <div class="shape pdp_point" data-shape="Circle" title="1" data-point-id="1" id="pdp_point_1">1</div>
        <div class="shape pdp_point" data-shape="Circle" title="2" data-point-id="2" id="pdp_point_2">2</div>
        <div class="shape pdp_point" data-shape="Circle" title="3" data-point-id="3" id="pdp_point_3">3</div>
        <div class="shape pdp_point" data-shape="Circle" title="4" data-point-id="4" id="pdp_point_4">4</div>
        <div class="shape pdp_point" data-shape="Circle" title="5" data-point-id="5" id="pdp_point_5">5</div>
        <div class="shape pdp_point" data-shape="Circle" title="6" data-point-id="6" id="pdp_point_6">6</div>
        <div class="shape pdp_point" data-shape="Circle" title="7" data-point-id="7" id="pdp_point_7">7</div>
        <div class="shape pdp_point" data-shape="Circle" title="8" data-point-id="8" id="pdp_point_8">8</div>
        <div class="shape pdp_point" data-shape="Circle" title="9" data-point-id="9" id="pdp_point_9">9</div>

        <div class="shape pdp_point" data-shape="Circle" title="10" data-point-id="10" id="pdp_point_10">10</div>
        <div class="shape pdp_point" data-shape="Circle" title="11" data-point-id="11" id="pdp_point_11">11</div>
        <div class="shape pdp_point" data-shape="Circle" title="12" data-point-id="12" id="pdp_point_12">12</div>
        <div class="shape pdp_point" data-shape="Circle" title="13" data-point-id="13" id="pdp_point_13">13</div>
        <div class="shape pdp_point" data-shape="Circle" title="14" data-point-id="14" id="pdp_point_14">14</div>
        <div class="shape pdp_point" data-shape="Circle" title="15" data-point-id="15" id="pdp_point_15">15</div>
        <div class="shape pdp_point" data-shape="Circle" title="16" data-point-id="16" id="pdp_point_16">16</div>
        <div class="shape pdp_point" data-shape="Circle" title="17" data-point-id="17" id="pdp_point_17">17</div>
        <div class="shape pdp_point" data-shape="Circle" title="18" data-point-id="18" id="pdp_point_18">18</div>
        <div class="shape pdp_point" data-shape="Circle" title="19" data-point-id="19" id="pdp_point_19">19</div>

        <div class="shape pdp_point" data-shape="Circle" title="20" data-point-id="20" id="pdp_point_20">20</div>
        <div class="shape pdp_point" data-shape="Circle" title="21" data-point-id="21" id="pdp_point_21">21</div>
        <div class="shape pdp_point" data-shape="Circle" title="22" data-point-id="22" id="pdp_point_22">22</div>
        <div class="shape pdp_point" data-shape="Circle" title="23" data-point-id="23" id="pdp_point_23">23</div>
        <div class="shape pdp_point" data-shape="Circle" title="24" data-point-id="24" id="pdp_point_24">24</div>
        <div class="shape pdp_point" data-shape="Circle" title="25" data-point-id="25" id="pdp_point_25">25</div>
        <div class="shape pdp_point" data-shape="Circle" title="26" data-point-id="26" id="pdp_point_26">26</div>
        <div class="shape pdp_point" data-shape="Circle" title="27" data-point-id="27" id="pdp_point_27">27</div>
        <div class="shape pdp_point" data-shape="Circle" title="28" data-point-id="28" id="pdp_point_28">28</div>
        <div class="shape pdp_point" data-shape="Circle" title="29" data-point-id="29" id="pdp_point_29">29</div>

        <div class="shape pdp_point" data-shape="Circle" title="30" data-point-id="30" id="pdp_point_30">30</div>
        <div class="shape pdp_point" data-shape="Circle" title="31" data-point-id="31" id="pdp_point_31">31</div>
        <div class="shape pdp_point" data-shape="Circle" title="32" data-point-id="32" id="pdp_point_32">32</div>
        <div class="shape pdp_point" data-shape="Circle" title="33" data-point-id="33" id="pdp_point_33">33</div>
        <div class="shape pdp_point" data-shape="Circle" title="34" data-point-id="34" id="pdp_point_34">34</div>
        <div class="shape pdp_point" data-shape="Circle" title="35" data-point-id="35" id="pdp_point_35">35</div>
        <div class="shape pdp_point" data-shape="Circle" title="36" data-point-id="36" id="pdp_point_36">36</div>
        <div class="shape pdp_point" data-shape="Circle" title="37" data-point-id="37" id="pdp_point_37">37</div>
        <div class="shape pdp_point" data-shape="Circle" title="38" data-point-id="38" id="pdp_point_38">38</div>
        <div class="shape pdp_point" data-shape="Circle" title="39" data-point-id="39" id="pdp_point_39">39</div>

        <div class="shape pdp_point" data-shape="Circle" title="40" data-point-id="40" id="pdp_point_40">40</div>
        <div class="shape pdp_point" data-shape="Circle" title="41" data-point-id="41" id="pdp_point_41">41</div>
        <div class="shape pdp_point" data-shape="Circle" title="42" data-point-id="42" id="pdp_point_42">42</div>
        <div class="shape pdp_point" data-shape="Circle" title="43" data-point-id="43" id="pdp_point_43">43</div>
        <div class="shape pdp_point" data-shape="Circle" title="44" data-point-id="44" id="pdp_point_44">44</div>
        <div class="shape pdp_point" data-shape="Circle" title="45" data-point-id="45" id="pdp_point_45">45</div>
        <div class="shape pdp_point" data-shape="Circle" title="46" data-point-id="46" id="pdp_point_46">46</div>
        <div class="shape pdp_point" data-shape="Circle" title="47" data-point-id="47" id="pdp_point_47">47</div>
        <div class="shape pdp_point" data-shape="Circle" title="48" data-point-id="48" id="pdp_point_48">48</div>
        <div class="shape pdp_point" data-shape="Circle" title="49" data-point-id="49" id="pdp_point_49">49</div>

        <div class="shape pdp_point" data-shape="Circle" title="50" data-point-id="50" id="pdp_point_50">50</div>
        <div class="shape pdp_point" data-shape="Circle" title="51" data-point-id="51" id="pdp_point_51">51</div>
        <div class="shape pdp_point" data-shape="Circle" title="52" data-point-id="52" id="pdp_point_52">52</div>
        <div class="shape pdp_point" data-shape="Circle" title="53" data-point-id="53" id="pdp_point_53">53</div>
        <div class="shape pdp_point" data-shape="Circle" title="54" data-point-id="54" id="pdp_point_54">54</div>
        <div class="shape pdp_point" data-shape="Circle" title="55" data-point-id="55" id="pdp_point_55">55</div>
        <div class="shape pdp_point" data-shape="Circle" title="56" data-point-id="56" id="pdp_point_56">56</div>
        <div class="shape pdp_point" data-shape="Circle" title="57" data-point-id="57" id="pdp_point_57">57</div>
        <div class="shape pdp_point" data-shape="Circle" title="58" data-point-id="58" id="pdp_point_58">58</div>
        <div class="shape pdp_point" data-shape="Circle" title="59" data-point-id="59" id="pdp_point_59">59</div>

        <div class="shape pdp_point" data-shape="Circle" title="60" data-point-id="60" id="pdp_point_60">60</div>
        <div class="shape pdp_point" data-shape="Circle" title="61" data-point-id="61" id="pdp_point_61">61</div>
        <div class="shape pdp_point" data-shape="Circle" title="62" data-point-id="62" id="pdp_point_62">62</div>
        <div class="shape pdp_point" data-shape="Circle" title="63" data-point-id="63" id="pdp_point_63">63</div>
        <div class="shape pdp_point" data-shape="Circle" title="64" data-point-id="64" id="pdp_point_64">64</div>
        <div class="shape pdp_point" data-shape="Circle" title="65" data-point-id="65" id="pdp_point_65">65</div>
        <div class="shape pdp_point" data-shape="Circle" title="66" data-point-id="66" id="pdp_point_66">66</div>
        <div class="shape pdp_point" data-shape="Circle" title="67" data-point-id="67" id="pdp_point_67">67</div>
        <div class="shape pdp_point" data-shape="Circle" title="68" data-point-id="68" id="pdp_point_68">68</div>
        <div class="shape pdp_point" data-shape="Circle" title="69" data-point-id="69" id="pdp_point_69">69</div>

        <div class="shape pdp_point" data-shape="Circle" title="70" data-point-id="70" id="pdp_point_70">70</div>
        <div class="shape pdp_point" data-shape="Circle" title="71" data-point-id="71" id="pdp_point_71">71</div>
        <div class="shape pdp_point" data-shape="Circle" title="72" data-point-id="72" id="pdp_point_72">72</div>
        <div class="shape pdp_point" data-shape="Circle" title="73" data-point-id="73" id="pdp_point_73">73</div>
        <div class="shape pdp_point" data-shape="Circle" title="74" data-point-id="74" id="pdp_point_74">74</div>
        <div class="shape pdp_point" data-shape="Circle" title="75" data-point-id="75" id="pdp_point_75">75</div>
        <div class="shape pdp_point" data-shape="Circle" title="76" data-point-id="76" id="pdp_point_76">76</div>
        <div class="shape pdp_point" data-shape="Circle" title="77" data-point-id="77" id="pdp_point_77">77</div>
        <div class="shape pdp_point" data-shape="Circle" title="78" data-point-id="78" id="pdp_point_78">78</div>
        <div class="shape pdp_point" data-shape="Circle" title="79" data-point-id="79" id="pdp_point_79">79</div>

        <div class="shape pdp_point" data-shape="Circle" title="80" data-point-id="80" id="pdp_point_80">80</div>
        <div class="shape pdp_point" data-shape="Circle" title="81" data-point-id="81" id="pdp_point_81">81</div>
        <div class="shape pdp_point" data-shape="Circle" title="82" data-point-id="82" id="pdp_point_82">82</div>
        <div class="shape pdp_point" data-shape="Circle" title="83" data-point-id="83" id="pdp_point_83">83</div>
        <div class="shape pdp_point" data-shape="Circle" title="84" data-point-id="84" id="pdp_point_84">84</div>
        <div class="shape pdp_point" data-shape="Circle" title="85" data-point-id="85" id="pdp_point_85">85</div>
        <div class="shape pdp_point" data-shape="Circle" title="86" data-point-id="86" id="pdp_point_86">86</div>
        <div class="shape pdp_point" data-shape="Circle" title="87" data-point-id="87" id="pdp_point_87">87</div>
        <div class="shape pdp_point" data-shape="Circle" title="88" data-point-id="88" id="pdp_point_88">88</div>
        <div class="shape pdp_point" data-shape="Circle" title="89" data-point-id="89" id="pdp_point_89">89</div>

        <div class="shape pdp_point" data-shape="Circle" title="90" data-point-id="90" id="pdp_point_90">90</div>
        <div class="shape pdp_point" data-shape="Circle" title="91" data-point-id="91" id="pdp_point_91">91</div>
        <div class="shape pdp_point" data-shape="Circle" title="92" data-point-id="92" id="pdp_point_92">92</div>
        <div class="shape pdp_point" data-shape="Circle" title="93" data-point-id="93" id="pdp_point_93">93</div>
        <div class="shape pdp_point" data-shape="Circle" title="94" data-point-id="94" id="pdp_point_94">94</div>
        <div class="shape pdp_point" data-shape="Circle" title="95" data-point-id="95" id="pdp_point_95">95</div>
        <div class="shape pdp_point" data-shape="Circle" title="96" data-point-id="96" id="pdp_point_96">96</div>
        <div class="shape pdp_point" data-shape="Circle" title="97" data-point-id="97" id="pdp_point_97">97</div>
        <div class="shape pdp_point" data-shape="Circle" title="98" data-point-id="98" id="pdp_point_98">98</div>
        <div class="shape pdp_point" data-shape="Circle" title="99" data-point-id="99" id="pdp_point_99">99</div>

        <div class="shape pdp_point" data-shape="Circle" title="100" data-point-id="100" id="pdp_point_100">100</div>

      </div>
      <script>
        jsPlumb.ready(function () {
          // GLOBAL DEF
          _pointsContainer = jsPlumb.getInstance({
            Connector: "StateMachine",
            PaintStyle: { lineWidth: 1, strokeStyle: "#ffa500" },
            Endpoint: ["Dot", { radius: 1 }],
            EndpointStyle: { fillStyle: "#ffa500" },
            Container: "points_container"
          });

          var pdpPoints = jsPlumb.getSelector(".shape");

          jsPlumb.fire("jsPlumbDemoLoaded", _pointsContainer);
        });

        function getPdpPoint(point_id) {
          var result = $("#points_container .shape#pdp_point_" + point_id);
          if (result.length === 0) { console.log('Cannot get point #' + point_id) };
          return result;
        }

        function getAllPdpPoints() {
          return $("#points_container .shape");
        }

        function refreshDepot(x, y) {
          depot = getPdpPoint(0);
          if (x) depot.css({ 'left': Math.floor(x) });
          if (y) depot.css({ 'top': Math.floor(y) });
          depot.show();
        }

        function refreshAllPdpPoints(tableData) {
          getAllPdpPoints().hide();

          var halfLength = Math.floor(tableData.length / 2);
          for (var i = 0; i < tableData.length; i++) {
            point = getPdpPoint(getPointIdFromRowNumber(i));
            if (typeof tableData[i][1] !== 'undefined') point.css({ 'left': Math.floor(parseFloat(tableData[i][0])) });
            if (typeof tableData[i][0] !== 'undefined') point.css({ 'top': Math.floor(parseFloat(tableData[i][1])) });

            point.removeClass('pickup');
            point.removeClass('delivery');
            point.addClass((i < halfLength) ? 'pickup' : 'delivery');
            point.data('shape', (i < halfLength) ? 'Triangle' : 'Circle');

            point.show();
          }

          refreshDepot();
        }

        function _clearAllPaths() {
          for (var cluster_index in _clusters) {
            _clearClusterPath(parseInt(cluster_index));
          }
        }

        function _clearClusterPath(cluster_index) {
          $.each(_clusters[cluster_index].points, function (index, pointId) {
            _pointsContainer.detachAllConnections(getPdpPoint(pointId));
          });
        }

        function _resetClusters() {
          _clusters = {
0: {
              points: Object.keys(_getPointsInfo()).map(function (pointId) {
                return parseInt(pointId);
              })
            }
};
        }

        function renderClusters(clusters) {
          // clean all custer classes from points
          var classes = ''; for (var i = 0; i < 50; i++) classes += 'cluster' + i + ' ';
          getAllPdpPoints().removeClass(classes);

          $("#cluster_container li").hide();

          for (var i = 0; i < Object.keys(clusters).length; i++) {
            clusters[i].points.forEach(function (pointId) {
              getPdpPoint(pointId).addClass('cluster' + i);
            });

            //place to edit description
            var pointsText = (Object.keys(clusters[i].points).length > 0) ? clusters[i].points.join(',') : '<no points>';
            $("#cluster_container #cluster" + i + " .description").html('<b>Points</b>:' + pointsText);

            if (_clusters[i].solution) {
              $("#cluster_container #cluster" + i + ' .console').html(
                "<b>Best path</b>: " + _clusters[i].solution.path.join(' ') +
                '<br/><b>Cost</b>:' + _clusters[i].solution.path_cost +
                '<br/><b>Solution time</b>: ' + _clusters[i].solution.solution_time.toFixed(2) + ' sec' +
                '<br/><b>Additional info</b>:' + JSON.stringify(_clusters[i].solution.info)
              );
            }
            else {
              $("#cluster_container #cluster" + i + ' .console').html("No path hasn't obtained for cluster yet");
            }

            $("#cluster_container #cluster" + i).show();
          }
        }


        function _drawClusterPath(cluster_index, drawIntervalMilliseconds) {
          drawIntervalMilliseconds = drawIntervalMilliseconds || 0;
          if (_clusters[cluster_index].solution) {
            drawPath(_pointsContainer, "points_container", _clusters[cluster_index].solution.path, $(".cluster" + cluster_index).css('backgroundColor'), drawIntervalMilliseconds);
          }
        }

        function _drawAllClusterPaths(drawIntervalMilliseconds) {
          for (var cluster_index in _clusters) {
            var cluster_index = parseInt(cluster_index);
            _drawClusterPath(cluster_index, drawIntervalMilliseconds);
          }
        }

        function getPointIdFromRowNumber(rowNumber) {
          return rowNumber + 1;
        };

        function getRowNumberFromPointId(pointId) {
          return pointId - 1;
        };


        function _getPointsInfo() {
          var data = _hot.getData();
          return data.reduce(function (array, value, i) {
            array[getPointIdFromRowNumber(i)] = value;
            return array;
          }, {});
        }

        function _getClusterPointsInfo(cluster_index) {
          if (!_clusters[cluster_index]) return false;

          var allPoints = _getPointsInfo();
          var clusterPoints = _clusters[cluster_index].points;

          var result = {};
          for (var pointId in allPoints) {
            pointId = parseInt(pointId);
            if (clusterPoints.indexOf(pointId) != -1) result[pointId] = allPoints[pointId];
          }

          return result;
        }

        function _getDepotInfo() {
          return [$('#depot_x').val(), $('#depot_y').val()];
        }

        function _getConfigInfo() {
          return {
            check_final_loading: $('#check_final_loading_checkbox').is(':checked'),
            check_transitional_loading_probability: $('#check_transitional_loading_probability').val(),
            python_file: $('#python_file').val(),
            precise: $('#precise').val(),
            weight_capacity: $('#weight_capacity').val(),
            load_area: {
              x: $('#load_area_x').val(),
              y: $('#load_area_y').val(),
              z: $('#load_area_z').val()
            }
          };
        }

        function _getSolveMethod() {
          return 'gen';
        }

        function getPointPositionsFromTableDataChanges(changes) {
          var positions = [];
          for (var i = 0; i < changes.length; i++) {
            // if column with point coordinates changed
            if ((changes[i][1] === 0) || (changes[i][1] === 1)) {
              dimension = (changes[i][1] === 0) ? 'left' : 'top';
              positions[i] = { 'point_id': getPointIdFromRowNumber(changes[i][0]) };
              positions[i][dimension] = parseFloat(changes[i][3]);
            }
          }

          return positions;
        };


        function getRandomBetween(min, max) {
          return Math.random() * (max - min) + min;
        }

        function fillTableRowWithRandomValues(table, rowNumber, fillOnlyCoords) {
          var minRandomCoord = 10;
          var maxRandomCoord = 500;
          var minRandomBoxSize = 1;
          var maxRandomBoxSize = 20;
          var minRandomBoxWeight = 1;
          var maxRandomBoxWeight = 20;

          // coords
          table.setDataAtCell(rowNumber, 0, getRandomBetween(minRandomCoord, maxRandomCoord).toFixed(2));
          table.setDataAtCell(rowNumber, 1, getRandomBetween(minRandomCoord, maxRandomCoord).toFixed(2));

          if (!fillOnlyCoords) {
            // box dimensions
            table.setDataAtCell(rowNumber, 2, getRandomBetween(minRandomBoxSize, maxRandomBoxSize).toFixed(2));
            table.setDataAtCell(rowNumber, 3, getRandomBetween(minRandomBoxSize, maxRandomBoxSize).toFixed(2));
            table.setDataAtCell(rowNumber, 4, getRandomBetween(minRandomBoxSize, maxRandomBoxSize).toFixed(2));

            // box weight
            table.setDataAtCell(rowNumber, 5, getRandomBetween(minRandomBoxWeight, maxRandomBoxWeight).toFixed(2));
          }
        }

        // adds or removes rows according to pairCount (number of PDP point pairs)
        function updateTablePairCount(table, newPairCount) {
          var rowCount = table.countRows();
          var oldPairCount = Math.floor(rowCount / 2);
          var diff = newPairCount - oldPairCount;

          if (diff === 0) {
            return;
          }

          if (diff < 0) {
            table.alter('remove_row', rowCount - Math.abs(diff), Math.abs(diff));
            table.alter('remove_row', newPairCount, Math.abs(diff));
          }
          if (diff > 0) {
            table.alter('insert_row', rowCount, diff);
            table.alter('insert_row', oldPairCount, diff);

            // if pair number increased, and we can fill cells with already loaded points, we do this
            // i.g., we:
            //  - loaded 5 point pairs
            //  - decreased number to 3
            //  - increased number to 7
            //  in this case, we want to add data about points 4 and 5 to table
            //
            // BUT we force new PDP points (points 6,7 from example above) to be empty. This is why we check 'data[data.length/2 + i]'
            var data = (typeof _lastLoadedData == 'undefined') ? [] : _lastLoadedData;
            for (var i = oldPairCount; i < (oldPairCount + diff); i++) {
              // if pair 'i' (BOTH pickup and delivery) loaded, restore them to table
              var useLoadedDataForNewRows = ((typeof data[i] !== 'undefined') && (typeof data[data.length / 2 + i] !== 'undefined'));
              if (useLoadedDataForNewRows) {
                for (var j = 0; j < table.countCols(); j++) {
                  table.setDataAtCell(i, j, data[i][j]);
                  table.setDataAtCell(newPairCount + i, j, data[data.length / 2 + i][j]);
                }
              }
              else {
                fillTableRowWithRandomValues(table, i);
                fillTableRowWithRandomValues(table, newPairCount + i);
              }
            }
          }
          //refresh table to redraw cells
          table.loadData(table.getData().slice(0));
        }

        function _sleep(milliSeconds) {
          var resource;
          var response;
          if (typeof ActiveXObject == 'undefined') {
            resource = new XMLHttpRequest();
          }
          else {
            // IE
            resource = new ActiveXObject("Microsoft.XMLHTTP");
          }

          try {
            resource.open('GET', '../api/sleep.php?milliSeconds=' + milliSeconds, false);
            resource.send(null);
            response = resource.responseText; // JavaScript waits for response
          }
          catch (e) {
            alert(e);
          }

          return true;
        }

        function connectPoints(pointsContainer, id1, id2, color) {
          pointsContainer.connect({
            source: getPdpPoint(id1),
            target: getPdpPoint(id2),
            connector: "Straight",
            overlays: ["Arrow"],
            paintStyle: { strokeStyle: color }
          });
          pointsContainer.repaintEverything();
        }

        function drawPath(pointsContainer, pointsContainerId, path, color, drawIntervalMilliseconds) {
          drawIntervalMilliseconds = drawIntervalMilliseconds || 0;

          // var pdpPoints = jsPlumb.getSelector(".shape");
          var container = document.getElementById(pointsContainerId);

          var n = 0;
          for (var i = 0; i < path.length - 1; i++) {
            setTimeout(function () {
              connectPoints(pointsContainer, path[n], path[n + 1], color);
              n++;
            }, i * drawIntervalMilliseconds);
          }

        }

        function _solveCluster(cluster_index) {
          var console = $("#cluster_container #cluster" + cluster_index + ' .console');

          var params = {
            method: _getSolveMethod(),
            config: _getConfigInfo(),
            data: {
              depot: _getDepotInfo(),
              points: _getClusterPointsInfo(cluster_index)
            }
          };

          if (Object.keys(params.data.points).length == 0) {
            return false;
          }

          console.text('Please, wait for solution ...');
          $.ajax({
            url: "../api/solve.php",
            type: "POST",
            data: 'params=' + JSON.stringify(params),
            dataType: "json",
            success: function (result) {
              if (result.path) {
                _clusters[cluster_index].solution = result;
                renderClusters(_clusters);
                _clearClusterPath(cluster_index);
                drawPath(_pointsContainer, "points_container", _clusters[cluster_index].solution.path, $(".cluster" + cluster_index).css('backgroundColor'));
              }
              else {
                console.text("Error: " + JSON.stringify(result));
              }
            },
            error: function (request, status, error) {
              alert('Technical error ' + request.status + ':' + request.responseText);
              console.text('Technical error ' + request.status + ':' + request.responseText);
            }
          });
        }

      </script>

  </div>

  </section>

  </div>

  <nav class="sidebar jsc-sidebar" id="jsi-nav" data-sidebar-options="">
    <div id="sidebar_container" class="container">
      <h1>Input data</h1>
      <div>
        <form action="" id="input_params">
          <p>
            <label>Vehicle count (
              <i>k</i>)</label>
            <input id="vehicle_count" size="1">
          </p>
          <p>
            <label>Vehicle load area</label>
            x:
            <input id="load_area_x" size="3"> y:
            <input id="load_area_y" size="3"> z:
            <input id="load_area_z" size="3">
          </p>
          <p>
            <label>Vehicle weight capacity (
              <i>Q</i>)</label>
            <input id="weight_capacity" size="5">
          </p>
          <hr>
          <p>
            <label>Precise (
              <i>RBW</i>), %</label>
            <input id="precise" size="5">
          </p>
          <p>
            <label for="check_final_loading_checkbox">Check 3D constraints for final paths</label>
            <input type="checkbox" id="check_final_loading_checkbox">
          </p>
          <p>
            <label>Check 3D constraints for partial paths probability(
              <i>check_prob</i>)</label>
            <input id="check_transitional_loading_probability" size="3">/100
          </p>
          <input type="hidden" id="python_file" size="45">
          <hr>
          <p>
            <label>Depot coords</label>
            X:
            <input id="depot_x" size="3"> Y:
            <input id="depot_y" size="3">
          </p>
          <p>
            <label for="pdp_pairs_number">Number of PDP pairs(
              <i>n</i>)</label>
            <input id="pdp_pairs_number" name="pdp_pairs_number" size="5">
          </p>
        </form>

        <label>PDP points</label>
        <div id="pdp_table"></div>
      </div>
      <h1>Clusters</h1>
      <div id="cluster_container">
        <ul>
          <li id="cluster0">
            <div>
              <span class="cluster0" data-shape="Circle">&nbsp;0</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <button class="solve_cluster_button" data-cluster-id="0">solve</button>
              <button class="draw_cluster_path_button" data-cluster-id="0">draw path</button>
              <button class="draw_cluster_path_step_by_step_button" data-cluster-id="0">draw path step by step</button>
              <button class="clear_cluster_path_button" data-cluster-id="0">clear path</button>
            </div>
            <br/>
            <div class="description">
              <b>Points</b>: </div>
            <div class="console"></div>
            <hr>
          </li>
          <li id="cluster1">
            <div>
              <span class="cluster1" data-shape="Circle">&nbsp;1</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <button class="solve_cluster_button" data-cluster-id="1">solve</button>
              <button class="draw_cluster_path_button" data-cluster-id="1">draw path</button>
              <button class="draw_cluster_path_step_by_step_button" data-cluster-id="1">draw path step by step</button>
              <button class="clear_cluster_path_button" data-cluster-id="1">clear path</button>
            </div>
            <br/>
            <div class="description">
              <b>Points</b>: </div>
            <div class="console"></div>
            <hr>
          </li>
          <li id="cluster2">
            <div>
              <span class="cluster2" data-shape="Circle">&nbsp;2</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <button class="solve_cluster_button" data-cluster-id="2">solve</button>
              <button class="draw_cluster_path_button" data-cluster-id="2">draw path</button>
              <button class="draw_cluster_path_step_by_step_button" data-cluster-id="2">draw path step by step</button>
              <button class="clear_cluster_path_button" data-cluster-id="2">clear path</button>
            </div>
            <br/>
            <div class="description">
              <b>Points</b>: </div>
            <div class="console"></div>
            <hr>
          </li>
          <li id="cluster3">
            <div>
              <span class="cluster3" data-shape="Circle">&nbsp;3</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <button class="solve_cluster_button" data-cluster-id="3">solve</button>
              <button class="draw_cluster_path_button" data-cluster-id="3">draw path</button>
              <button class="draw_cluster_path_step_by_step_button" data-cluster-id="3">draw path step by step</button>
              <button class="clear_cluster_path_button" data-cluster-id="3">clear path</button>
            </div>
            <br/>
            <div class="description">
              <b>Points</b>: </div>
            <div class="console"></div>
            <hr>
          </li>
          <li id="cluster4">
            <div>
              <span class="cluster4" data-shape="Circle">&nbsp;4</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <button class="solve_cluster_button" data-cluster-id="4">solve</button>
              <button class="draw_cluster_path_button" data-cluster-id="4">draw path</button>
              <button class="draw_cluster_path_step_by_step_button" data-cluster-id="4">draw path step by step</button>
              <button class="clear_cluster_path_button" data-cluster-id="4">clear path</button>
            </div>
            <br/>
            <div class="description">
              <b>Points</b>: </div>
            <div class="console"></div>
            <hr>
          </li>
          <li id="cluster5">
            <div>
              <span class="cluster5" data-shape="Circle">&nbsp;5</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <button class="solve_cluster_button" data-cluster-id="5">solve</button>
              <button class="draw_cluster_path_button" data-cluster-id="5">draw path</button>
              <button class="draw_cluster_path_step_by_step_button" data-cluster-id="5">draw path step by step</button>
              <button class="clear_cluster_path_button" data-cluster-id="5">clear path</button>
            </div>
            <br/>
            <div class="description">
              <b>Points</b>: </div>
            <div class="console"></div>
            <hr>
          </li>
          <li id="cluster6">
            <div>
              <span class="cluster6" data-shape="Circle">&nbsp;6</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <button class="solve_cluster_button" data-cluster-id="6">solve</button>
              <button class="draw_cluster_path_button" data-cluster-id="6">draw path</button>
              <button class="draw_cluster_path_step_by_step_button" data-cluster-id="6">draw path step by step</button>
              <button class="clear_cluster_path_button" data-cluster-id="6">clear path</button>
            </div>
            <br/>
            <div class="description">
              <b>Points</b>: </div>
            <div class="console"></div>
            <hr>
          </li>
          <li id="cluster7">
            <div>
              <span class="cluster7" data-shape="Circle">&nbsp;7</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <button class="solve_cluster_button" data-cluster-id="7">solve</button>
              <button class="draw_cluster_path_button" data-cluster-id="7">draw path</button>
              <button class="draw_cluster_path_step_by_step_button" data-cluster-id="7">draw path step by step</button>
              <button class="clear_cluster_path_button" data-cluster-id="7">clear path</button>
            </div>
            <br/>
            <div class="description">
              <b>Points</b>: </div>
            <div class="console"></div>
            <hr>
          </li>
          <li id="cluster8">
            <div>
              <span class="cluster8" data-shape="Circle">&nbsp;8</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <button class="solve_cluster_button" data-cluster-id="8">solve</button>
              <button class="draw_cluster_path_button" data-cluster-id="8">draw path</button>
              <button class="draw_cluster_path_step_by_step_button" data-cluster-id="8">draw path step by step</button>
              <button class="clear_cluster_path_button" data-cluster-id="8">clear path</button>
            </div>
            <br/>
            <div class="description">
              <b>Points</b>: </div>
            <div class="console"></div>
            <hr>
          </li>
          <li id="cluster9">
            <div>
              <span class="cluster9" data-shape="Circle">&nbsp;9</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <button class="solve_cluster_button" data-cluster-id="9">solve</button>
              <button class="draw_cluster_path_button" data-cluster-id="9">draw path</button>
              <button class="draw_cluster_path_step_by_step_button" data-cluster-id="9">draw path step by step</button>
              <button class="clear_cluster_path_button" data-cluster-id="9">clear path</button>
            </div>
            <br/>
            <div class="description">
              <b>Points</b>: </div>
            <div class="console"></div>
            <hr>
          </li>
          <li id="cluster10">
            <div>
              <span class="cluster10" data-shape="Circle">&nbsp;10</span>;1;1;1;1;1
              <button class="solve_cluster_button" data-cluster-id="0">solve</button>
              <button class="draw_cluster_path_button" data-cluster-id="0">draw path</button>
              <button class="draw_cluster_path_step_by_step_button" data-cluster-id="0">draw path step by step</button>
              <button class="clear_cluster_path_button" data-cluster-id="0">clear path</button>
            </div>
            <br/>
            <div class="description">
              <b>Points</b>: </div>
            <div class="console"></div>
            <hr>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <script>

  </script>

  <script src="js/sidebar/sidebar.js"></script>

  <script>

    $(function () {
      // var
      get = function (id) {
        return document.getElementById(id);
      },
        container = get('pdp_table'),
        exampleConsole = get('general_console'),
        autosave = get('autosave'),
        load = get('load'),
        save = get('save');
      _filenameContainer = get('pdp_points_filename');

      pickupRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.borderColor = 'green';
        td.style.borderWidth = '2px';
      };
      deliveryRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.backgroundColor = 'green';
      };

      disabledRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.backgroundColor = 'grey';
      }

      _hot = new Handsontable(container, {
        startRows: _pairCount * 2,
        startCols: 6,
        rowHeaders: true,
        colHeaders: true,
        minSpareRows: 0,
        currentRowClassName: 'currentRow',
        colHeaders: ['X', 'Y', 'box x', 'box y', 'box z', 'box weight'],
        columnSorting: true,
        // contextMenu: true,
        contextMenuCopyPaste: {
          swfPath: 'js/handsontable/zeroclipboard/ZeroClipboard.swf'
        },
        cells: function (row, col, prop) {
          if (row < (this.instance.countRows() / 2)) {
            this.renderer = pickupRenderer;
          }
          else {
            this.renderer = deliveryRenderer;
            if (col > 1) {
              var cellProperties = {};
              cellProperties.readOnly = true;
              cellProperties.renderer = disabledRenderer;
              return cellProperties;
            }
          }
        },
        beforeChange: function (changes, source) {
          //changes[i][0] - row number
          //changes[i][1] - column number
          //changes[i][3] - new value

          // remove all non-number symbols
          for (var i = changes.length - 1; i >= 0; i--) {
            if (changes[i][3]) {
              changes[i][3] = changes[i][3].toString().replace(/[^0-9\.]/g, '');

              // don't allow to fill box fields for delivery points
              if ((changes[i][1] > 1) && changes[i][0] >= Math.floor(this.getData().length / 2)) // if changed cell column > 1 and this is delivery point, ..
              {
                changes[i][3] = '';
              }
            }
          }
        },
        afterChange: function (changes, source) {
          if (typeof (_clusters) !== 'undefined') {
            _resetClusters();
            renderClusters(_clusters);
          }

          refreshAllPdpPoints(this.getData());
          if (typeof _pointsContainer !== "undefined") {
            _pointsContainer.detachEveryConnection();
          }

          if (changes) {
            var newPairCount = Math.floor(this.getData().length / 2);
            var oldPairCount = $("#pdp_pairs_number").spinner().spinner("value");
            if (newPairCount != oldPairCount) {
              $("#pdp_pairs_number").spinner().spinner("value", newPairCount);
            }

          }
        },
        afterLoadData: function () {
          var newPairCount = Math.floor(this.getData().length / 2);
          var oldPairCount = $("#pdp_pairs_number").spinner().spinner("value");
          if (newPairCount != oldPairCount) {
            $("#pdp_pairs_number").spinner().spinner("value", newPairCount);
          }

          refreshAllPdpPoints(this.getData());
        },
        afterSelectionEndByProp: function (row, col) {
          getAllPdpPoints().removeClass('highlighted');
          getPdpPoint(getPointIdFromRowNumber(row)).addClass('highlighted');
        }

      });

      loadJsonToTable = function (table, file) {
        if (file) {
          var reader = new FileReader();
          reader.onload = function () {
            try {
              var data = JSON.parse(this.result);

              // GLOBAL DEF
              _lastLoadedData = data.data;

              exampleConsole.innerHTML = 'Data loaded';
              // console.log(_lastLoadedData.slice(0));
              table.loadData(_lastLoadedData.slice(0));
            }
            catch (e) {
              exampleConsole.innerHTML = 'Data is incorrect. <br/>' + e;
            }
          };

          reader.readAsText(file);
        }
      };

      loadJsonFromFileContainerToMainTable = function () {
        loadJsonToTable(_hot, _filenameContainer.files[0]);
      };

      // Handsontable.Dom.addEvent(load, 'click', function(){
      //     loadJsonFromFileContainerToMainTable();
      // });

      // Handsontable.Dom.addEvent(save, 'click', function() {
      //   // // save all cell's data
      //   ajax('json/save.json', 'GET', JSON.stringify({data: _hot.getData()}), function (res) {
      //     var response = JSON.parse(res.response);

      //     if (response.result === 'ok') {
      //       exampleConsole.innerHTML = 'Data saved';
      //     }
      //     else {
      //       exampleConsole.innerHTML = 'Save error';
      //     }
      //   });
      // });

      $('#jsi-nav').sidebar({
        trigger: '.jsc-sidebar-trigger',
        scrollbarDisplay: true
      });

      $('#api-pull').on('click', function (e) {
        e.preventDefault();
        $('#jsi-nav').data('sidebar').push();
      });
      $('#api-push').on('click', function (e) {
        e.preventDefault();
        $('#jsi-nav').data('sidebar').pull();
      });

      // open sidebar at the begin
      $('#jsi-nav').data('sidebar').push();
    });
  </script>


  <script>
    // remove all non-numbers from all elements with class 'numbersOnly'
    // jQuery('.htInvalid').keyup(function () {
    //     this.value = this.value.replace(/[^0-9\.]/g,'');
    // });

    $('#pdp_points_filename').on("change", function () { loadJsonFromFileContainerToMainTable(); });
  </script>

  <script>

    $(function () {

      $("#sidebar_container").accordion({
        collapsible: true
      });

      // fill spinner with initial pair count
      $("#pdp_pairs_number").spinner().spinner("value", _pairCount);
      // fill table with random data
      for (var i = 0; i < (_pairCount * 2); i++) {
        fillTableRowWithRandomValues(_hot, i, (i >= _pairCount));
      };

      $("#pdp_pairs_number").spinner({
        min: 2,
        change: function (event, ui) {
          _pairCount = parseInt($("#pdp_pairs_number").spinner().spinner("value"));
          updateTablePairCount(_hot, _pairCount);
        }
      });

      // attach highlight events. begin
      $("#points_container").on('mouseover', ".pickup", function () {
        getPdpPoint(parseInt($(this).data("point-id")) + Math.floor(_hot.countRows() / 2)).addClass('highlighted');
        $(this).addClass('highlighted');
      });

      $("#points_container").on('mouseout', ".pickup", function () {
        $(".delivery").removeClass('highlighted');
        $(this).removeClass('highlighted');
      });

      $("#points_container").on('mouseover', ".delivery", function () {
        getPdpPoint(parseInt($(this).data("point-id")) - Math.floor(_hot.countRows() / 2)).addClass('highlighted');
        $(this).addClass('highlighted');
      });

      $("#points_container").on('mouseout', ".delivery", function () {
        $(".pickup").removeClass('highlighted');
        $(this).removeClass('highlighted');
      });
      // attach highlight events. end

      $("#points_container").height(window.innerHeight - 140);

      $('#depot_x').change(function () {
        refreshDepot($(this).val(), undefined);
      });
      $('#depot_y').change(function () {
        refreshDepot(undefined, $(this).val());
      });

      $("#vehicle_count").change(function () {
        renderClusters(_clusters);
      });

      $('#depot_x').val(_default_depot_coords.x);
      $('#depot_y').val(_default_depot_coords.y);
      $('#depot_x').trigger('change');
      $('#depot_y').trigger('change');

      $('#precise').val(_default_precise);
      $('#weight_capacity').val(_default_weight_capacity);
      $('#load_area_x').val(_default_load_area.x);
      $('#load_area_y').val(_default_load_area.y);
      $('#load_area_z').val(_default_load_area.z);

      $("#vehicle_count").val(_default_vehicle_count);

      $("#python_file").val(_default_python_file);
      $('#check_transitional_loading_probability').val(_default_check_transitional_loading_probability);
      _resetClusters();
      renderClusters(_clusters);
    });


    // function drawPath(pointsContainer, pointsContainerId, path, drawIntervalMilliseconds) {

    //     pointsContainer.detachEveryConnection();
    //     // var pdpPoints = jsPlumb.getSelector(".shape");
    //     var container = document.getElementById(pointsContainerId);
    //     for (var i=0; i < path.length-1; i++) {
    //         pointsContainer.connect({
    //             source: getPdpPoint(path[i]),
    //             target: getPdpPoint(path[i+1]),
    //             connector:"Straight",
    //             overlays:["Arrow"]
    //         });

    //         if (drawIntervalMilliseconds && (i<path.length-1)) {
    //           _sleep(drawIntervalMilliseconds);
    //         }
    //     };
    //     pointsContainer.repaintEverything();
    // };



    $('.solve_cluster_button').on('click', function (event) {
      var cluster_index = $(event.target).data('cluster-id');
      _solveCluster(cluster_index);
    });

    $('.draw_cluster_path_button').on('click', function (event) {
      var cluster_index = $(event.target).data('cluster-id');
      if (_clusters[cluster_index].solution) {
        _clearClusterPath(cluster_index);
        _drawClusterPath(cluster_index, 0);
      }
    });

    $('.draw_cluster_path_step_by_step_button').on('click', function (event) {
      var cluster_index = $(event.target).data('cluster-id');
      if (_clusters[cluster_index].solution) {
        _clearClusterPath(cluster_index);
        _drawClusterPath(cluster_index, 500);
      }
    });

    $('.clear_cluster_path_button').click(function (event) {
      var cluster_index = $(event.target).data('cluster-id');
      _clearClusterPath(cluster_index);
    });

    // ---

    $('#reset_clusters_button').click(function () {
      _resetClusters();
      _clearAllPaths();
      renderClusters(_clusters);
    });

    $('#solve_gen_all_button').on('click', function (event) {
      for (var cluster_index in _clusters) {
        _solveCluster(parseInt(cluster_index));
      }
    });

    $('#clean_all_connections_button').click(function () {
      _clearAllPaths();
    });

    $('#draw_all_paths_button').on('click', function () {
      _clearAllPaths();
      _drawAllClusterPaths(0);
    });

    $('#draw_all_paths_step_by_step_button').on('click', function () {
      _clearAllPaths();
      _drawAllClusterPaths(500);
    });

    $('#export_points_button').click(function () {
      console.log(JSON.stringify(_hot.getData(), null, 2));
    });

    var loadClusters = function(clusters) {
      console.log("Loading clusters");
      console.log(clusters);

      var text = '';

      _resetClusters();
      clusters.forEach(function (cluster, cluster_index) {
        /* PHP returns point ids from 0. We convert them to fronend point ids (from 1)*/
        cluster.forEach(function (elem, point_index) {
          var pointId = getPointIdFromRowNumber(cluster[point_index]);
          cluster[point_index] = pointId;
        });


        // numerize clusters from 1
        text += 'Cluster ' + (cluster_index) + ': points ' + cluster.join(',') + ' . ';

        _clusters[cluster_index] = {};
        _clusters[cluster_index].points = cluster;
      });

      renderClusters(_clusters);
      _clearAllPaths();

      $("#general_console").text(text);
    };

    $('#import_clusters_button').click(function () {
      console.log(JSON.stringify(_hot.getData(), null, 2));
    });

    $('#clusterize_button').click(function () {
      $("#general_console").text('Please, wait for solution ...');

      $.ajax({
        url: "../api/clusterize.php",
        type: "POST",
        data: 'params=' + JSON.stringify({ cluster_count: $("#vehicle_count").val(), points: _hot.getData() }),
        dataType: "json",
        success: function (result) {
          if (result.clusters) {
            loadClusters(result.clusters);

            // $("#general_console").text("Best path is: " + result.path.join(' ') + " with cost " + result.path_cost + '. solution time: ' +result.solution_time.toFixed(2) + ' sec. Additional info:' + JSON.stringify(result.info));
            // drawPath(_pointsContainer, "points_container",  result.path);
          }
          else {
            $("#general_console").text("Error: " + JSON.stringify(result));
          }
        },
        error: function (request, status, error) {
          $("#general_console").text('Technical error ' + request.status + ':' + error);
        }
      });
    });
  </script>
</body>

</html>