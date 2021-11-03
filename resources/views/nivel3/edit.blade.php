@extends('layouts.app')

@section('content')


<div id="sample">
    <div  class="float-end">
        <p class="text" id="getID">
            {{$nivel3->title_slug}}
        </p> 
    </div>
    <div>
        <h3>{{$nivel3->title}} - autor ({{$nivel3->author_name}})</h3>
    </div>
    
    <div onmouseleave="Update()" style="width: 100%; display: flex; justify-content: space-between">
        <div id="myPaletteDiv" style="width: 140px; margin-right: 2px; background-color: whitesmoke; border: solid 1px black"></div>
        <div id="myDiagramDiv" style="flex-grow: 1; height: 620px; border: solid 1px black"></div>
        {{--<div class="card text-dark bg-light" style="width: 150px; margin-left: 2px;  border: solid 1px black">
            <h5 class="card-header">Mensajes</h5>
            
            <div class="card-body" id="messages">
                <div class="card-title">
                    <input type="text" name="username" id="username" placeholder="Usuario" class="col-12">
                </div>
                <div class="anyClass">
                  <div id="messages"></div>
                </div>
            </div>
            <div class="card-footer">
              <form id="message_form">
                <div class="col-xs-2">
                    <input type="text" name="message" id="message_input" placeholder="Mensaje" class="col-12">
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                  <button type="submit" id="message_send" class="btn btn-primary btn-sm">Enviar</button>
                </div>
              </form>
            </div>
        </div>--}}
    </div>
    <br>
    <div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
            <button id="SaveButton" class="btn btn-success" onclick="save()">Guardar</button>
            <button class="btn btn-primary" onclick="load()">Cargar</button>
            <button class="btn btn-info" onclick="printDiagram()">Imprimir</button>
            <a href="{{route('nivel3.index')}}" class="btn btn-secondary">Volver Atrás</a> 
            Modelos de diagrama guardados en formato JSON:
        </div>
        <br>
        <textarea id="mySavedModel" style="width:100%;height:300px">
            @if ($nivel3->json_data == null)
            { "class": "go.GraphLinksModel",
                "linkFromPortIdProperty": "fromPort",
                "linkToPortIdProperty": "toPort",
                "nodeDataArray": [
                ],
                "linkDataArray": [
                ]}
            @else
                {{$nivel3->json_data}}
            @endif
        </textarea>
    </div>
</div>


<script id="code">
    function init() {
      var $ = go.GraphObject.make;  // Para definir templates

      myDiagram =
        $(go.Diagram, "myDiagramDiv",  // Referencia al div de HTML
          {           
            grid: $(go.Panel, "Grid",
              $(go.Shape, "LineH", { stroke: "lightgray", strokeWidth: 0.5 }),
              $(go.Shape, "LineH", { stroke: "gray", strokeWidth: 0.5, interval: 10 }),
              $(go.Shape, "LineV", { stroke: "lightgray", strokeWidth: 0.5 }),
              $(go.Shape, "LineV", { stroke: "gray", strokeWidth: 0.5, interval: 10 })
            ),
            "draggingTool.dragsLink": true,
            "draggingTool.isGridSnapEnabled": true,
            "linkingTool.isUnconnectedLinkValid": true,
            "linkingTool.portGravity": 20,
            "relinkingTool.isUnconnectedLinkValid": true,
            "relinkingTool.portGravity": 20,
            "relinkingTool.fromHandleArchetype":
              $(go.Shape, "Diamond", { segmentIndex: 0, cursor: "pointer", desiredSize: new go.Size(8, 8), fill: "tomato", stroke: "darkred" }),
            "relinkingTool.toHandleArchetype":
              $(go.Shape, "Diamond", { segmentIndex: -1, cursor: "pointer", desiredSize: new go.Size(8, 8), fill: "darkred", stroke: "tomato" }),
            "linkReshapingTool.handleArchetype":
              $(go.Shape, "Diamond", { desiredSize: new go.Size(7, 7), fill: "lightblue", stroke: "deepskyblue" }),
            "rotatingTool.handleAngle": 270,
            "rotatingTool.handleDistance": 30,
            "rotatingTool.snapAngleMultiple": 15,
            "rotatingTool.snapAngleEpsilon": 15,
            "undoManager.isEnabled": true
          });

      // Cuando el documento es modificado, se añade un * (asterisco) al titulo y habilita el boton de guardar
      myDiagram.addDiagramListener("Modified", function(e) {
        //var button = document.getElementById("SaveButton");
        //if (button) button.disabled = !myDiagram.isModified;
        var idx = document.title.indexOf("*");
        if (myDiagram.isModified) {
          if (idx < 0) document.title += "*";
        } else {
          if (idx >= 0) document.title = document.title.substr(0, idx);
        }
      });

        /*
            Definir una funcion para crear "port" puertos que normalmente son transparentes
            el "name" es usado como GraphObject.portId, el "spot" lugar es usado para controlar 
            como los "links" enlaces se conectan y donde o que puerto esta posicionada el nodo,
            y los argumentos bool "output" y "input" controla en donde el usuario dibuja enlaces
            o que puerto.
        */
      function makePort(name, spot, output, input) {  //los puertos son circulos transparente pequeños
        return $(go.Shape, "Circle",
          {
            fill: null,  //no se ve
            stroke: null,
            desiredSize: new go.Size(7, 7),
            alignment: spot,  // alinear el puerto sobre la figura principal
            alignmentFocus: spot,  // justo dentro de la figura
            portId: name,  // declarar el objeto para que sea un puerto "port"
            fromSpot: spot, toSpot: spot,  // declarar hacia donde se va a enlazar a este puerto
            fromLinkable: output, toLinkable: input,  // declarar lo que el usuario va a dibujar el enlace de/hasta
            cursor: "pointer"  // mostrar un cursos diferente a la cual indica un posible enlace
          });
      }

      var nodeSelectionAdornmentTemplate =
        $(go.Adornment, "Auto",
          $(go.Shape, { fill: null, stroke: "deepskyblue", strokeWidth: 1.5, strokeDashArray: [4, 2] }),
          $(go.Placeholder)
        );

      var nodeResizeAdornmentTemplate =
        $(go.Adornment, "Spot",
          { locationSpot: go.Spot.Right },
          $(go.Placeholder),
          $(go.Shape, { alignment: go.Spot.TopLeft, cursor: "nw-resize", desiredSize: new go.Size(6, 6), fill: "lightblue", stroke: "deepskyblue" }),
          $(go.Shape, { alignment: go.Spot.Top, cursor: "n-resize", desiredSize: new go.Size(6, 6), fill: "lightblue", stroke: "deepskyblue" }),
          $(go.Shape, { alignment: go.Spot.TopRight, cursor: "ne-resize", desiredSize: new go.Size(6, 6), fill: "lightblue", stroke: "deepskyblue" }),

          $(go.Shape, { alignment: go.Spot.Left, cursor: "w-resize", desiredSize: new go.Size(6, 6), fill: "lightblue", stroke: "deepskyblue" }),
          $(go.Shape, { alignment: go.Spot.Right, cursor: "e-resize", desiredSize: new go.Size(6, 6), fill: "lightblue", stroke: "deepskyblue" }),

          $(go.Shape, { alignment: go.Spot.BottomLeft, cursor: "se-resize", desiredSize: new go.Size(6, 6), fill: "lightblue", stroke: "deepskyblue" }),
          $(go.Shape, { alignment: go.Spot.Bottom, cursor: "s-resize", desiredSize: new go.Size(6, 6), fill: "lightblue", stroke: "deepskyblue" }),
          $(go.Shape, { alignment: go.Spot.BottomRight, cursor: "sw-resize", desiredSize: new go.Size(6, 6), fill: "lightblue", stroke: "deepskyblue" })
        );

      var nodeRotateAdornmentTemplate =
        $(go.Adornment,
          { locationSpot: go.Spot.Center, locationObjectName: "ELLIPSE" },
          $(go.Shape, "Ellipse", { name: "ELLIPSE", cursor: "pointer", desiredSize: new go.Size(7, 7), fill: "lightblue", stroke: "deepskyblue" }),
          $(go.Shape, { geometryString: "M3.5 7 L3.5 30", isGeometryPositioned: true, stroke: "deepskyblue", strokeWidth: 1.5, strokeDashArray: [4, 2] })
        );

      myDiagram.nodeTemplate =
        $(go.Node, "Spot",
          { locationSpot: go.Spot.Center },
          new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
          { selectable: true, selectionAdornmentTemplate: nodeSelectionAdornmentTemplate },
          { resizable: true, resizeObjectName: "PANEL", resizeAdornmentTemplate: nodeResizeAdornmentTemplate },
          { rotatable: true, rotateAdornmentTemplate: nodeRotateAdornmentTemplate },
          new go.Binding("angle").makeTwoWay(),
          // el objeto principal es un Panel que rondea a un bloque de texto con una figura
          $(go.Panel, "Auto",
            { name: "PANEL" },
            new go.Binding("desiredSize", "size", go.Size.parse).makeTwoWay(go.Size.stringify),
            $(go.Shape, "Rectangle",  // figura principal
              {
                portId: "", // el puerto por defecto, si no detecta un "spot" usa al mas cercano
                fromLinkable: true, toLinkable: true, cursor: "pointer",
                fill: "white",  // color por defecto
                strokeWidth: 2
              },
              new go.Binding("figure"),
              new go.Binding("fill")),
            $(go.TextBlock,
              {
                font: "bold 8pt Helvetica, Arial, sans-serif",
                margin: 8,
                maxSize: new go.Size(160, NaN),
                wrap: go.TextBlock.WrapFit,
                editable: true
              },
              new go.Binding("text").makeTwoWay())
          ),
          // 4 pequeños puertos con nombres cada lado 
          makePort("T", go.Spot.Top, false, true),
          makePort("L", go.Spot.Left, true, true),
          makePort("R", go.Spot.Right, true, true),
          makePort("B", go.Spot.Bottom, true, false),
          { // maneja el evento del raton entrada/salida para mostrar/ocultar los puertos
            mouseEnter: function(e, node) { showSmallPorts(node, true); },
            mouseLeave: function(e, node) { showSmallPorts(node, false); }
          }
        );

      function showSmallPorts(node, show) {
        node.ports.each(function(port) {
          if (port.portId !== "") {  // no cambiar el puerto por defecto, en la cual es la figura grande
            port.fill = show ? "rgba(0,0,0,.3)" : null;
          }
        });
      }

      var linkSelectionAdornmentTemplate =
        $(go.Adornment, "Link",
          $(go.Shape,
            // isPanelMain declara que esta figura comparte su Link.geometry
            { isPanelMain: true, fill: null, stroke: "deepskyblue", strokeWidth: 0 })  // use selection object's strokeWidth
        );

      myDiagram.linkTemplate =
        $(go.Link,  // todo el panel de enlace
          { selectable: true, selectionAdornmentTemplate: linkSelectionAdornmentTemplate },
          { relinkableFrom: true, relinkableTo: true, reshapable: true },
          {
            routing: go.Link.AvoidsNodes,
            curve: go.Link.JumpOver,
            corner: 5,
            toShortLength: 4
          },
          new go.Binding("points").makeTwoWay(),
          $(go.Shape,  // la figura del camino de enlace
            { isPanelMain: true, strokeWidth: 2 }),
          $(go.Shape,  // la cabezera de la flecha
            { toArrow: "Standard", stroke: null }),
          $(go.Panel, "Auto",
            new go.Binding("visible", "isSelected").ofObject(),
            $(go.Shape, "RoundedRectangle",  // la figura del enlace
              { fill: "#F8F8F8", stroke: null }),
            $(go.TextBlock,
              {
                textAlign: "center",
                font: "10pt helvetica, arial, sans-serif",
                stroke: "#919191",
                margin: 2,
                minSize: new go.Size(10, NaN),
                editable: true
              },
              new go.Binding("text").makeTwoWay())
          )
        );

      load();  // carga un diagrama inicial de algun fomato en JSON


//-----------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------


      // initialize the Palette that is on the left side of the page
      myPalette =
        $(go.Palette, "myPaletteDiv",  // Refiere al elemento div
          {
            maxSelectionCount: 1,
            nodeTemplateMap: myDiagram.nodeTemplateMap,  // comparte el template usado en myDiagram
            linkTemplate: // simplifica el enlace del template, solo en este palette
              $(go.Link,
                { 
                    /*
                        Por que el GridLayout.alignmet es ubicacion y los nodos tiene locationSpot == Spot.Center,
                        que se alinea con el enlace en el mismo modo tenemo que pretender que el enlace tiene la misma
                        ubicacion spot
                    */
                  locationSpot: go.Spot.Center,
                  selectionAdornmentTemplate:
                    $(go.Adornment, "Link",
                      { locationSpot: go.Spot.Center },
                      $(go.Shape,
                        { isPanelMain: true, fill: null, stroke: "deepskyblue", strokeWidth: 0 }),
                      $(go.Shape,  // la flecha
                        { toArrow: "Standard", stroke: null })
                    )
                },
                {
                  routing: go.Link.AvoidsNodes,
                  curve: go.Link.JumpOver,
                  corner: 5,
                  toShortLength: 4
                },
                new go.Binding("points"),
                $(go.Shape,  // la figura del camino del enlace
                  { isPanelMain: true, strokeWidth: 2 }),
                $(go.Shape,  // la cabezera de la flecha
                  { toArrow: "Standard", stroke: null })
              ),
            model: new go.GraphLinksModel([  // especifica el contenido de palette
                { text: "", figure: "Rectangle", fill: "transparent", size: "95, 70" },
                { text: "Componente", figure: "Rectangle", fill: "lightskyblue" },
                { text: "DB", figure: "Triangle", fill: "indianred"},
                { text: "App Movil", figure: "Border", fill: "skyblue"},
                { text: "Pagina Web", figure: "Border", fill: "skyblue"},
                { text: "Sistema Existente", figure: "Rectangle", fill: "lightgray" },
            ], [
                // el palette tambien tiene un enlace desconectado, que nos permitira arrastar al panel
                { points: new go.List(/*go.Point*/).addAll([new go.Point(0, 0), new go.Point(30, 0), new go.Point(30, 40), new go.Point(60, 40)]) },
              ])
          });
    }


//-----------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------

    //Pusher.logToConsole = true; 
    const pusher = new Pusher(
        "729e19586eb2111ddef1", // Replace with 'key' from dashboard
      {
        cluster: "mt1", // Replace with 'cluster' from dashboard
        forceTLS: true,
      }
    );
    const channel = pusher.subscribe("diag3-update");
    channel.bind("diag3", (data) => {
      myDiagram.model = go.Model.fromJson(data.json);
      loadDiagramProperties();
    });

    function Update(){
      saveDiagramProperties(); 
      const options = {
        method: 'post',
        url: '/send-diag3-update',
        data: {
          json: myDiagram.model.toJson(),
        },
      }
      myDiagram.isModified = false;
      axios(options);
    }

    // Muestra el modelo del diagrama en formato JSON que el usuario editara
    function save() {
      saveDiagramProperties();  // hacer esto antes de escribir JSON
      document.getElementById("mySavedModel").value = myDiagram.model.toJson();
      myDiagram.isModified = false;
      const options = {
        method: 'post',
        url: '/update-nivel3',
        data: {
          title_slug: '{{$nivel3->title_slug}}',
          json_data: myDiagram.model.toJson(),
        },
      }
      axios(options);
    }
    function load() {
      myDiagram.model = go.Model.fromJson(document.getElementById("mySavedModel").value);
      loadDiagramProperties();  // hacer esto despues de Model.modelData fue recuperado de la memoria
    }

    function saveDiagramProperties() {
      myDiagram.model.modelData.position = go.Point.stringify(myDiagram.position);
    }
    function loadDiagramProperties(e) {
      // set Diagram.initialPosition, not Diagram.position, para manejar los efectos secundarios de la inicializacion 
      var pos = myDiagram.model.modelData.position;
      if (pos) myDiagram.initialPosition = go.Point.parse(pos);
    }
    function printDiagram() {
      var svgWindow = window.open();
      if (!svgWindow) return;  // failure to open a new Window
      var printSize = new go.Size(700, 960);
      var bnds = myDiagram.documentBounds;
      var x = bnds.x;
      var y = bnds.y;
      while (y < bnds.bottom) {
        while (x < bnds.right) {
          var svg = myDiagram.makeSvg({ scale: 1.0, position: new go.Point(x, y), size: printSize });
          svgWindow.document.body.appendChild(svg);
          x += printSize.width;
        }
        x = bnds.x;
        y += printSize.height;
      }
      setTimeout(function() { svgWindow.print(); }, 1);
    }
    
    window.addEventListener('DOMContentLoaded', init);
</script>

@endsection

{{--{ "class": "GraphLinksModel",
  "linkFromPortIdProperty": "fromPort",
  "linkToPortIdProperty": "toPort",
  "modelData": {"position":"-215 -283.6820068359375"},
  "nodeDataArray": [
{"text":"Existing System","figure":"Rectangle","fill":"lightgray","key":-4,"loc":"-90 -150"},
{"text":"","figure":"Rectangle","fill":"transparent","size":"95, 70","key":-5,"loc":"60 -10"}
],
  "linkDataArray": [{"points":[-39.8253173828125,-150,-29.8253173828125,-150,60,-150,60,-102.5,60,-55,60,-45],"from":-4,"to":-5,"fromPort":"R","toPort":"T"}]}--}}