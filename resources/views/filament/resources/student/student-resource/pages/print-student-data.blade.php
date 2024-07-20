<link rel="stylesheet" href="{{asset('print.css')}}">

<div class="printable">
    @foreach($students as $student)
        <div  style="width: 210mm;height: 297mm; background: white" class="mx-auto my-8 p-4 flex  items-center">
            <div class="border relative w-full h-full">
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
                  <div class="flex justify-between p-2">
                      <div></div>
                      <img class="rounded w-20 h-20" src="{{asset('storage/'.$student->image)}}">
                  </div>

                   <table class="w-full">
                       <tr class="border">
                           <td class="p-2 py-3 border font-bold bg-gray-100/50">
                               ناوی منداڵ
                           </td>
                           <td class="p-2">
                               {{$student->name}}
                           </td>
                       </tr>
                       <tr class="border">
                           <td class="p-2 py-3 border font-bold bg-gray-100/50">
                               بەرواری لەدایک بوون
                           </td>
                           <td class="p-2">
                               {{$student->birthdate}}
                           </td>
                       </tr>
                       <tr class="border">
                           <td class="p-2 py-3 border font-bold bg-gray-100/50">
                               بەرواری وەرگرتن
                           </td>
                           <td class="p-2">
                               {{$student->start_date}}
                           </td>
                       </tr>
                       <tr class="border">
                           <td class="p-2 py-3 border font-bold bg-gray-100/50">
                               قۆناغ
                           </td>
                           <td class="p-2">
                               {{$student->stage->name}}
                           </td>
                       </tr>

                       <tr class="border">
                           <td class="p-2 py-3 border font-bold bg-gray-100/50">
                               ناوی باوک
                           </td>
                           <td class="p-2">
                               {{$student->parents->father_name}}
                           </td>
                       </tr>
                       <tr class="border">
                           <td class="p-2 py-3 border font-bold bg-gray-100/50">
                               ناوی دایک
                           </td>
                           <td class="p-2">
                               {{$student->parents->mother_name}}
                           </td>
                       </tr>
                       <tr class="border">
                           <td class="p-2 py-3 border font-bold bg-gray-100/50">
                               ژمارەی مۆبایلی باوک
                           </td>
                           <td class="p-2">
                               {{$student->parents->father_phone}}
                           </td>
                       </tr>
                       <tr class="border">
                           <td class="p-2 py-3 border font-bold bg-gray-100/50">
                               ژمارەی مۆبایلی دایک
                           </td>
                           <td class="p-2">
                               {{$student->parents->mother_phone}}
                           </td>
                       </tr>
                       <tr class="border">
                           <td class="p-2 py-3 border font-bold bg-gray-100/50">
                               پیشەی باوک
                           </td>
                           <td class="p-2">
                               {{$student->parents->father_work}}
                           </td>
                       </tr>
                       <tr class="border">
                           <td class="p-2 py-3 border font-bold bg-gray-100/50">
                               پیشەی دایک
                           </td>
                           <td class="p-2">
                               {{$student->parents->mother_work}}
                           </td>
                       </tr>
                       <tr class="border">
                           <td class="p-2 py-3 border font-bold bg-gray-100/50">
                               کۆد
                           </td>
                           <td class="p-2">
                               {{$student->code}}
                           </td>
                       </tr>
                       <tr class="border">
                           <td class="p-2 py-3 border font-bold bg-gray-100/50">
                               کتێب
                           </td>
                           <td class="p-2">
                               {{$student->book? 'بەڵێ' : 'نەخێر'}}
                           </td>
                       </tr>
                       <tr class="border">
                           <td class="p-2 py-3 border font-bold bg-gray-100/50">
                               جلوبەرگ
                           </td>
                           <td class="p-2">
                               {{$student->clothes? 'بەڵێ' : 'نەخێر'}}
                           </td>
                       </tr>
                       <tr class="border">
                           <td class="p-2 py-3 border font-bold bg-gray-100/50">
                               پشکنین
                           </td>
                           <td class="p-2">
                               {{$student->check? 'بەڵێ' : 'نەخێر'}}
                           </td>
                       </tr>
                       <tr class="border">
                           <td colspan="2" class="p-2">
                           <b>ناونیشان: </b>    {{$student->address}}
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

        </div>
    @endforeach
</div>
