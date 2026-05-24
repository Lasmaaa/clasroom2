@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Rediģēt uzdevumu</h4>
        </div>
        <div class="card-body">

            <form method="POST" action="{{ route('teacher.task.update', $task) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Uzdevuma nosaukums</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $task->title) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Apraksts</label>
                    <textarea name="description" class="form-control" rows="6" required>{{ old('description', $task->description) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Termiņš</label>
                            <input type="datetime-local" name="deadline" class="form-control" 
                                   value="{{ old('deadline', $task->deadline ? $task->deadline->format('Y-m-d\TH:i') : '') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Maksimālais punktu skaits</label>
                            <input type="number" name="points" class="form-control" 
                                   value="{{ old('points', $task->points) }}">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-primary">Saglabāt izmaiņas</button>
                    
                    <button type="button" class="btn btn-danger" 
                            onclick="if(confirm('Vai tiešām izdzēst šo uzdevumu?')) 
                            document.getElementById('delete-form').submit()">
                        🗑 Izdzēst uzdevumu
                    </button>
                </div>
            </form>

            <!-- Delete forma -->
            <form id="delete-form" action="{{ route('teacher.task.delete', $task) }}" method="POST" style="display:none">
                @csrf
                @method('DELETE')
            </form>

        </div>
    </div>

    <a href="{{ route('teacher.class.show', $task->class_id ?? $task->classroom_id) }}" class="btn btn-secondary mt-3">
        ← Atpakaļ uz klasi
    </a>
</div>
@endsection