const editorElement = document.querySelector('#route-editor');

if (editorElement && typeof L !== 'undefined') {
    const form = document.querySelector('[data-route-form]');
    const routeInput = form.querySelector('input[name="route"]');
    const countText = document.querySelector('[data-count]');
    const selectionBox = document.querySelector('[data-selection]');
    const selectionLabel = document.querySelector('[data-selection-label]');
    const undoButton = form.querySelector('[data-undo]');
    const saveButton = form.querySelector('[data-save]');

    const MAX_POINTS = 500;
    const data = readMapData(editorElement);

    let route = data.route.map(([lat, lng]) => [lat, lng]);
    let history = [];
    let selected = null;

    const map = L.map(editorElement);
    addTiles(map, data);

    const line = L.polyline(route, { color: 'red', weight: 5, dashArray: '5,8', lineJoin: 'round', interactive: false }).addTo(map);
    const markers = L.layerGroup().addTo(map);

    if (route.length) {
        map.fitBounds(line.getBounds(), { maxZoom: 17 });
    } else {
        map.setView(GRONINGEN, 15);
    }

    function pointIcon(index) {
        const isSelected = index === selected;
        return L.divIcon({
            className: '',
            iconSize: [22, 22],
            iconAnchor: [11, 11],
            html: `<span class="flex items-center justify-center w-[22px] h-[22px] rounded-full border-2 border-text text-[10px] text-text shadow-md ${isSelected ? 'bg-accent2' : 'bg-card'}">${index + 1}</span>`,
        });
    }

    // Bewaart de huidige route zodat "Ongedaan maken" ernaar terug kan
    function remember() {
        history.push(route.map((point) => [...point]));
    }

    function render() {
        line.setLatLngs(route);
        markers.clearLayers();

        route.forEach((point, index) => {
            const marker = L.marker(point, { icon: pointIcon(index), draggable: true, autoPan: true }).addTo(markers);

            marker.on('click', () => {
                selected = selected === index ? null : index;
                render();
            });
            marker.on('dragstart', remember);
            marker.on('drag', () => {
                const { lat, lng } = marker.getLatLng();
                route[index] = [lat, lng];
                line.setLatLngs(route);
            });
            marker.on('dragend', render);
        });

        countText.textContent = route.length;
        selectionBox.classList.toggle('hidden', selected === null);
        selectionLabel.textContent = selected === null ? '' : `Punt ${selected + 1} geselecteerd`;
        undoButton.disabled = !history.length;
        undoButton.classList.toggle('opacity-50', !history.length);
        saveButton.disabled = route.length < 2;
        saveButton.classList.toggle('opacity-50', route.length < 2);
        routeInput.value = JSON.stringify(route);
    }

    map.on('click', (event) => {
        if (selected !== null) {
            selected = null;
        } else if (route.length < MAX_POINTS) {
            remember();
            route.push([event.latlng.lat, event.latlng.lng]);
        } else {
            alert(`Een route kan maximaal ${MAX_POINTS} punten hebben.`);
        }
        render();
    });

    document.querySelector('[data-delete]').addEventListener('click', () => {
        remember();
        route.splice(selected, 1);
        selected = null;
        render();
    });

    undoButton.addEventListener('click', () => {
        if (history.length) {
            route = history.pop();
            selected = null;
            render();
        }
    });

    form.querySelector('[data-clear]').addEventListener('click', () => {
        if (route.length && confirm('Weet je zeker dat je alle punten van de route wilt wissen?')) {
            remember();
            route = [];
            selected = null;
            render();
        }
    });

    render();
}
