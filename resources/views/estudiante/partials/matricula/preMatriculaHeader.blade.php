<header class="mb-5">
  <img height="120px" src="{{ asset('img/escudo.png') }}" alt="escudo colegio simon bolivar">
  <div class="text-center">
    <h2>Pre-Matrícula {{ now()->year }}</h2>
    <h2>Colegio Simón Bolívar</h3>
  </div>
  <div>
    <h5 class="text-center">Fecha:</h5>
    <h5 class="text-center">{{ now()->toDateString() }}</h5>
  </div>
</header>