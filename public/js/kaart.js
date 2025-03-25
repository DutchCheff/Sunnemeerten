let kaart = document.querySelector("#kaart");

let map = L.map('kaart').setView([53.22, 6.56], 15);
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

let pathCoordinates = [
    [53.22144972390063,6.562589424460544],
    [53.22095946990001,6.560718781620076],
    [53.22058996579885,6.5609710001823105],
    [53.22021100962282,6.559780097838771],
    [53.219581974636526,6.558159578233106],
    [53.219325223007644,6.557504931240092],
    [53.219216103099335,6.55746736952739],
    [53.21880850568918,6.557875182408255],
    [53.21850688724483,6.558401790655146],
    [53.21833999467226,6.5587344801106084],
    [53.2180832355986,6.5591315610736],
    [53.21774302745531,6.559437420734238],
    [53.21803509310096,6.560660859376953],
    [53.21818593941278,6.56140672767224],
    [53.21826296711179,6.561970153362983],
    [53.218067188105394,6.562340404531147],
    [53.21799336955921,6.5623833322028045],
    [53.218128168547594,6.562995051524161],
    [53.218494049377725,6.56265699610974],
    [53.218847089321265,6.562340404531147],
    [53.219038377146624,6.563149279038482], 
    [53.21915391634384,6.563610751508958], 
    [53.219404250201904,6.5644532070655535], 
    [53.21890044323922,6.564935403725794], 
    [53.21858591727724,6.565300288935002], 
    [53.21823608466779,6.565751029487563], 
    [53.21791513480664,6.566083718943029], 
    [53.217895877738485,6.566158842368469], 
    [53.21790229676218,6.566271527506609], 
    [53.218069191040115,6.566690072305428], 
    [53.21818794234192,6.567038859637765], 
    [53.218300274351435,6.5673554512163586], 
    [53.21839013974695,6.5676022853284675], 
    [53.21868541043332,6.567655944918078]
]

var polyline = L.polyline(pathCoordinates, {
    color: 'red',
    weight: 5,
    opacity: 1.0,
    smoothFactor: 1.0,
    dashArray: '5,8',
    lineJoin: 'round',
}).addTo(map);

let startMarker = L.marker([53.22144972390063,6.562589424460544]).bindPopup("<div class='text-center text-xl josefin-sans'><b>Start</b></div>").addTo(map);
let eindeMarker = L.marker([53.21868541043332,6.567655944918078]).bindPopup("<div class='text-center text-xl josefin-sans'><b>Einde</b></div>").addTo(map);

let route = L.layerGroup([polyline]).addTo(map);
let start = L.layerGroup([startMarker]).addTo(map);
let einde = L.layerGroup([eindeMarker]).addTo(map);

let overlay = {
    "Route": route,
    "Start": start,
    "Einde": einde,
};

L.control.layers({}, overlay).addTo(map);

map.fitBounds(polyline.getBounds());

// map.on("contextmenu", function (event) {
//     console.log("Coordinates: " + event.latlng.toString());
//     coordinates += "[" + [event.latlng.lat, event.latlng.lng] + "], ";
// });

// map.on("keypress", (key) => {
//     console.log(key.originalEvent.key);
//     if (key.originalEvent.key == "Enter") {
//         console.log(coordinates);
//     }
// });