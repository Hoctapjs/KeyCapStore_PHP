@if(session('success'))
<x-ui.alert type="success">{{ session('success') }}</x-ui.alert>
@endif
@if(session('error'))
<x-ui.alert type="danger">{{ session('error') }}</x-ui.alert>
@endif