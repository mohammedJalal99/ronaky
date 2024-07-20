<link rel="stylesheet" href="{{asset('print.css')}}">

<div class="printable">
        <div  style="width: 210mm;height: 297mm; background: white" class="mx-auto my-8 p-4">
            @for($i=0;$i<2;$i++)
            <div class="border relative w-full h-full" style="height: 140mm; @if($i == 1) margin-top:5mm @endif">
                <div class="w-full text-center justify-between p-4 flex items-center border-b font-bold text-3xl">
                    <div>
                        <img src="{{asset('images/logo2.png')}}" class="h-20">
                    </div>
                    <div class="text-center">
                        <div>
                            باخچەی منداڵانی ڕۆناکی ناحکومی
                        </div>
                        <div class="flex items-center gap-8 justify-between">
                            <div class="text-base">
                                مەکتەبە دەرمانی ئێمە
                            </div>
                            <div class="text-base">
                                خوێندنە ڕابەری ڕزگاری
                            </div>
                        </div>
                    </div>
                    <div>
                        <img src="{{asset('images/logo.jpg')}}" class="h-20">
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex items-center align-middle justify-between">
                        <div>
                            ژمارە: {{$data->id}}
                        </div>
                        <div style="color: red" class=" text-lg">
                            پسولەی پارەدان
                        </div>
                        <div>
                            بەروار: {{$data->created_at->format('Y-m-d')}}
                        </div>
                    </div>
                    <hr class="my-2">
                    <table class="w-full mt-4">
                        <tr class="border">
                            <td class="p-2 py-3 border font-bold bg-gray-100/50">
                                ناوی منداڵ
                            </td>
                            <td class="p-2">
                                {{$data->student->name}}
                            </td>
                        </tr>
                        <tr class="border">
                            <td class="p-2 py-3 border font-bold bg-gray-100/50">
                                ناوی باوک
                            </td>
                            <td class="p-2">
                                {{$data->student->parents->father_name}}
                            </td>
                        </tr>
                        <tr class="border">
                            <td class="p-2 py-3 border font-bold bg-gray-100/50">
                                بڕی پارە
                            </td>
                            <td class="p-2">
                                {{number_format($data->amount,$data->currency->decimal_places)}} {{$data->currency->symbol}}
                            </td>
                        </tr>
                        <tr class="border">
                            <td class="p-2 py-3 border font-bold bg-gray-100/50">
                                بڕی ماوە
                            </td>
                            <td class="p-2">
                                {{number_format(\App\Models\Student\Student::dueAmount($data->student->id,$data->created_at),2)}} $
                            </td>
                        </tr>
                        <tr class="border">
                            <td colspan="2" class="p-2 py-3 border font-bold">
                                <div class="flex items-center align-middle gap-2 justify-between">
                                  <div class="flex items-center align-middle gap-2 w-1/2">
                                      <div>
                                          ژمارەی قست:
                                      </div>
                                      <div>
                                          {{number_format(\App\Models\Student\Student::installmentNumber($data->student->id,$data->created_at))}}
                                      </div>
                                  </div>
                                  <div class="flex items-center align-middle gap-2 w-1/2">
                                      <div>
                                          قستی ماوە:
                                      </div>
                                      <div>
                                          {{number_format($data->student->installments - \App\Models\Student\Student::installmentNumber($data->student->id,$data->created_at))}}
                                      </div>
                                  </div>
                                </div>
                            </td>
                        </tr>

                    </table>
                </div>
                <div class="flex text-sm p-2 border-t w-full absolute bottom-0 items-center justify-between align-middle">
                    <div>
                        ناونیشان:  هەولێر/ شەقامی ڕۆناکی - نزیک تەلەفیزۆنی ڕووداو
                    </div>
                    <div class="flex items-center gap-2">
                        <div>
                            ژمارەی مۆبایل :
                        </div>
                        <div dir="ltr" class="flex gap-2">
                            <div dir="ltr" class="text-start">
                                0750 481 9595
                            </div>
                            <div>
                                -
                            </div>
                            <div dir="ltr" class="text-start">
                                0771 688 6161
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
</div>
