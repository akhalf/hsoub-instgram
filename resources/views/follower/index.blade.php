@extends('layouts.app')

@section('content')
<div class="album py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="my-3 p-3 bg-white rounded box-shadow" style="direction:  rtl;text-align:  right;">
                    <h6 class="border-bottom border-gray pb-2 mb-0">طلبات المتابعة</h6>
                    <!-- Foreach -->
                    @foreach($follow_requests as $request)
                    <div class="media text-muted pt-3">
                        <img src="{{ asset('images/avatar/'.$request->from_user->avatar) }}" alt="" class="col-sm-2 mr-2 rounded" style="margin-right: -3%;width: 50px;height: 50px;">
                        <div class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray" >
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <strong class="text-gray-dark">{{ $request->from_user->name }}</strong>

                                <!-- رفض الطلب -->
                                <form method="POST" action="{{ route('follow.destroy', $request->id) }}">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-outline-warning">refuse</button>
                                </form>

                                <!-- قبول الطلب -->
                                <form method="POST" action="{{ route('follow.update', $request->id) }}">
                                    @method('PATCH')
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success">accept</button>
                                </form>
                            </div>
                            <span class="d-block">تاريخ الطلب {{ $request->created_at }}</span>
                        </div>
                    </div>
                    @endforeach
                    <!-- End Foreach -->
                    <small class="d-block text-right mt-3">
                        <a href="#">جميع الطلبات</a>
                    </small>
                </div>
            </div>
            <!-- Part 2 -->
            <div class="col-md-6">
                <div class="my-3 p-3 bg-white rounded box-shadow" style="direction:  rtl;text-align:  right;">
                    <h6 class="border-bottom border-gray pb-2 mb-0">الأصدقاء</h6>
                    <!-- Foreach -->
                    @foreach($followers as $follower)
                    <!-- User define -->
                        @php
                            $user = $follower->from_user_id == auth()->id() ? $follower->to_user : $follower->from_user;
                        @endphp
                    <div class="media text-muted pt-3">
                        <img src="{{ asset('images/avatar/'.$user->avatar) }}" alt="" class="col-sm-2 mr-2 rounded" style="margin-right: -3%;width: 50px;height: 50px;">
                        <div class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <a href=""><strong class="text-gray-dark">{{ $user->name }}</strong></a>
                                <!-- حذف الصداقة -->
                            </div>
                            <span class="d-block">{{ $follower->created_at }}</span>
                        </div>
                    </div>
                    <!-- End Foreach -->
                        <form method="POST" action="{{ route('follow.destroy', $follower->id) }}">
                            @method('DELETE')
                            @csrf
                            <input type="submit" class="btn btn-outline-danger" value="delete">
                        </form>
                    @endforeach
                    <small class="d-block text-right mt-3">
                        <a href="#">جميع التحديثات</a>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
