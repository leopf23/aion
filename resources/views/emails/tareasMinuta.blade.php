<!DOCTYPE html>
<html>

<body>

    <h1>{{ $mailData['alias'] }}</h1>
    <hr>
    <br>
    <p>

        Estan son las tareas creadas durante la minuta.
        @foreach ($mailData['tarea'] as $tarea)
            <br>
            <br>
            Titulo: <a href="http://localhost:8000/tareas/{{ $tarea['id'] }}/detalle"> {{ $tarea['tarea'] }}</a>
            <br>
            Responsable: {{ $tarea['responsable']->name }}
            <br>
            Estatus: {{ $tarea['estatus']->titulo }}
            <br>
            Fecha de entrega: {{ $tarea['fecha'] }}
            <br>
        @endforeach




    </p>
    <br>
    <p>verificar detalles en <a href="http://localhost:8000"> AION </a></p>
</body>

</html>