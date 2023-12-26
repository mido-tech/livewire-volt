<?php
use function Livewire\Volt\{state,rules,computed};
use App\Models\Todo;
state(['name'=>'']);
rules(['name'=>'required']);
$todos= computed(function(){
    return Todo::orderBy('completed')->get();
});
$save= function(){
    $validated=$this->validate();
    Todo::create($validated);
    $this->reset();
    session()->flash('status','New todo saved');

};
$completed= function(Todo $todo){
    $todo->update([
        'completed'=>1
    ]);
    session()->flash('status','todo completed');

};
$delete= function(Todo $todo){
     $todo->delete();
     session()->flash('status','todo deleted');

    };
?>
<x-layouts.app>
    @volt
<div>
<div class="card">
  <h5 class="card-header">Customize</h5>
  <div class="card-body">
    <div>
        @if(session('status'))
        <div class="alert alert-success">
            {{session('status')}}
        </div>
        @endif
    </div>
  <form wire:submit="save">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Todo</label>
    <input wire:model="name" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    <div>
        @error('name') <span class="text-danger">{{$message}}</span> @enderror
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
  </div>
</div>
<table class="table">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">First</th>
      <th scope="col">Action</th>
      
    </tr>
  </thead>
  <tbody>
    @foreach($this->todos as $todo)
    <tr @if($todo->completed==1) class="table-primary" @endif>
      <th scope="row">{{$loop->index+1}}</th>
      <td>{{$todo->name}}</td>
      <td>
        <button wire:click="delete({{$todo->id}})" class="btn btn-danger btn-sm float-end">Delete</button>
        @if($todo->completed==0)
        <button wire:click="completed({{$todo->id}})" class="btn btn-success btn-sm float-end">Completed</button>
        @endif
      </td>
      
    </tr>
    @endforeach
  </tbody>
</table>
</div>
@endvolt
</x-layouts.app>
