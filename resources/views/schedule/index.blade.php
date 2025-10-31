@extends('layouts.app')

@section('title', 'Lịch chiếu')

@push('styles')
<style>
  :root{
    --bg:#0f1115; --panel:#171b23; --panel-2:#222733; --text:#e6e6e6; --muted:#9aa3b2; --accent:#f5c768;
    --radius:14px; --stroke:#2f3748; --stroke-active:#3e485f;
  }
  body{background:var(--bg);}
  .week-strip{display:flex; margin:18px 0 24px;}
  .week-scroll{display:flex; gap:12px; overflow-x:auto; padding-bottom:6px;}
  .week-scroll::-webkit-scrollbar{height:8px}
  .week-scroll::-webkit-scrollbar-thumb{background:#2a3040; border-radius:8px}

  /* mỗi NGÀY là 1 KHUNG */
  .day-card{
    min-width:140px; background:var(--panel); border-radius:var(--radius);
    padding:12px 14px; border:1px solid var(--stroke);
    display:flex; flex-direction:column; gap:4px; transition:transform .15s ease, border-color .15s, background .15s;
  }
  .day-card .date{font-weight:700}
  .day-card .weekday{color:var(--muted)}
  .day-card:hover{transform:translateY(-1px); border-color:#47536b}
  .day-card.active{background:linear-gradient(180deg,#2a3142,#232a3b);
    border-color:var(--stroke-active); box-shadow:0 8px 20px rgba(0,0,0,.25)}
  .day-card.active .weekday{color:var(--accent); font-weight:600}

  .arrow-btn{background:transparent; border:0; color:var(--muted); font-size:22px; width:40px; height:40px; border-radius:999px}
  .arrow-btn:hover{color:#fff; background:#232a3b}

  /* Timeline lanes */
  .timeline{position:relative; margin-top:6px}
  .lane{position:relative; padding-left:64px; margin-bottom:26px}
  .lane:before{content:''; position:absolute; top:20px; bottom:6px; left:32px; width:1px; background:#2d3444}
  .time-badge{position:absolute; left:0; top:0; color:var(--muted); font-weight:600; font-size:12px}

  .slot-row{display:flex; flex-wrap:wrap; gap:18px}
  .show-card{background:var(--panel-2); border-radius:20px; padding:14px 16px; min-width:290px;
    display:flex; align-items:center; gap:14px; box-shadow:0 1px 0 rgba(255,255,255,.03), 0 10px 24px rgba(0,0,0,.25)}
  .show-card:hover{transform:translateY(-1px)}
  .poster{width:48px; height:64px; border-radius:10px; object-fit:cover}
  .show-title{margin:0; font-weight:600}
  .show-sub{color:var(--muted); font-size:13px}
  .dot{width:6px; height:6px; border-radius:999px; background:#41495e}

  /* Responsive */
  @media (max-width: 576px){
    .day-card{min-width:120px}
    .lane{padding-left:56px}
  }
</style>
@endpush

@section('content')
<div class="container schedule-wrap py-3">

  {{-- Title --}}
  <div class="page-title mb-2">
    <span class="icon">📅</span> <span>Lịch chiếu</span>
  </div>

  {{-- Week strip --}}
  <div class="week-strip">
  <div class="week-scroll" id="weekScroll">
    @php $vnDays = ['Chủ nhật','Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7']; @endphp
    @foreach($weekDates as $d)
      @php
        $isActive = $d->isSameDay($activeDate);
        $url = route('schedule.index', ['date' => $d->toDateString()]);
      @endphp
      <a href="{{ $url }}" class="text-decoration-none text-reset">
        <div class="day-card {{ $isActive ? 'active' : '' }}">
          <div class="date">{{ $d->format('d/m') }}</div>
          <div class="weekday">{{ $vnDays[$d->dayOfWeek] }}</div>
        </div>
      </a>
    @endforeach
  </div>
</div>

  {{-- Timeline of selected date --}}
  @php
    // Gom theo thời điểm (HH:MM) để render lanes giống ảnh: 03:00, 21:30, ...
    $groups = $items->groupBy(fn($st) => $st->start_time->format('H:i'));
    // Chia mỗi group thành 2 hàng để có cảm giác "hai lane" xếp so le
    $split = function($collection){
      $a = collect(); $b = collect(); $i=0;
      foreach ($collection as $x){ ($i++ % 2 ? $b : $a)->push($x); }
      return [$a,$b];
    };
  @endphp

  @forelse($groups as $time => $list)
  @php
    [$row1, $row2] = $split($list);
@endphp

    <div class="timeline">
      <div class="lane">
        <div class="time-badge">{{ $time }}</div>
        <div class="slot-row">
          @foreach($row1 as $st)
            <a href="{{ route('movies.show', $st->movie->slug) }}" class="text-reset text-decoration-none">
              <div class="show-card">
              <img class="poster"
     src="{{ $st->movie->poster ? \Storage::url($st->movie->poster) : asset('images/placeholder.png') }}"
     alt="">
                <div>
                  <p class="show-title">{{ $st->movie->title }}</p>
                  @php $ep = $st->episode_number ?? $st->movie->episode_number ?? null; @endphp
                  @if($ep)
                    <div class="show-sub">Tập {{ $ep }}</div>
                  @endif
                </div>
              </div>
            </a>
          @endforeach
        </div>
      </div>

      @if($row2->isNotEmpty())
      <div class="lane">
        <div class="time-badge" style="top:0; opacity:0">.</div>
        <div class="slot-row">
          @foreach($row2 as $st)
            <a href="{{ route('movies.show', $st->movie->slug) }}" class="text-reset text-decoration-none">
              <div class="show-card">
              <img class="poster"
     src="{{ $st->movie->poster ? \Storage::url($st->movie->poster) : asset('images/placeholder.png') }}"
     alt="">
                <div>
                  <p class="show-title">{{ $st->movie->title }}</p>
                  @php $ep = $st->episode_number ?? $st->movie->episode_number ?? null; @endphp
                  @if($ep)
                    <div class="show-sub">Tập {{ $ep }}</div>
                  @endif
                </div>
              </div>
            </a>
          @endforeach
        </div>
      </div>
      @endif
    </div>
  @empty
    <div class="alert alert-secondary bg-transparent border rounded-3 text-light">
      Chưa có lịch chiếu cho ngày {{ $activeDate->format('d/m/Y') }}.
    </div>
  @endforelse
</div>
@endsection

@push('scripts')
<script>
  (function(){
    const scroller = document.getElementById('weekScroll');
    document.getElementById('weekPrev').addEventListener('click', ()=> scroller.scrollBy({left:-240, behavior:'smooth'}));
    document.getElementById('weekNext').addEventListener('click', ()=> scroller.scrollBy({left: 240, behavior:'smooth'}));
    // auto-center active
    const active = scroller.querySelector('.day-card.active');
    if(active){
      const rect = active.getBoundingClientRect();
      const srect = scroller.getBoundingClientRect();
      scroller.scrollBy({left: (rect.left - srect.left) - (srect.width/2 - rect.width/2), behavior:'instant'});
    }
  })();
</script>
@endpush
