<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Submit Event Proposal') }}
    </h2>
  </x-slot>

  <style>
    .calendar {
      display: flex;
      position: relative;
      padding: 16px;
      margin: 0 auto;
      width: 100%;
      background: white;
      border-radius: 4px;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .month-year {
      position: absolute;
      bottom: 62px;
      right: -27px;
      font-size: 2rem;
      line-height: 1;
      font-weight: 300;
      color: #94A3B8;
      transform: rotate(90deg);
      -webkit-transform: rotate(90deg);
      -moz-transform: rotate(90deg);
      -ms-transform: rotate(90deg);
    }

    .year {
      margin-left: 4px;
      color: #CBD5E1;
    }

    .days {
      display: flex;
      flex-wrap: wrap;
      flex-grow: 1;
      margin-right: 46px;
    }

    .day-label {
      position: relative;
      flex-basis: calc(14.286% - 2px);
      margin: 1px 1px 12px 1px;
      font-weight: 700;
      font-size: 0.65rem;
      text-transform: uppercase;
      color: #1E293B;
    }

    .day {
      position: relative;
      flex-basis: calc(14.286% - 2px);
      margin: 1px;
      /* border-radius: 999px; */
      cursor: pointer;
      font-weight: 300;
    }

    .day.dull {
      color: #94A3B8;
    }

    .day.today {
      color: #0EA5E9;
      font-weight: 600;
    }

    .day::before {
      content: '';
      display: block;
      padding-top: 100%;
    }

    .day:hover {
      background: #E0F2FE;
    }

    .day .content {
      /* position: absolute;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      display: flex; */
      /* justify-content: center;
      align-items: center; */
    }
  </style>

  <div>

    <div class="p-4 overflow-y-auto">
      <label for="input-label" class="block text-sm font-medium mb-2 dark:text-white">Email</label>
      <form method="post" action="{{ route('calendar.post-calendar-event') }}">
        @csrf

        <input type="date" id="date" name="date">
        <x-input-error :messages="$errors->get('date')" class="mt-2" />
        <input type="text" id="event" name="event"
          class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:placeholder-neutral-500 dark:text-neutral-400"
          placeholder="Enter event">
        <x-input-error :messages="$errors->get('event')" class="mt-2" />
        <button type="submit">Submit</button>
      </form>
    </div>


    <div class="calendar">
      <div class="month-year">
        <span class="month">{{ $date->format('M') }}</span>
        <span class="year">{{ $date->format('Y') }}</span>
      </div>
      <div class="days">
        @php
          $dayLabels = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        @endphp
        @foreach ($dayLabels as $dayLabel)
          <span class="day-label">{{ $dayLabel }}</span>
        @endforeach
        @while ($startOfCalendar <= $endOfCalendar)
          @php
            $count = 0;
            $extraClass = $startOfCalendar->format('m') != $date->format('m') ? 'dull' : '';
            $extraClass = $startOfCalendar->isToday() ? ' today' : '';
          @endphp
          <div class="day {{ $extraClass }}">
            <span class="content">{{ $startOfCalendar->format('j') }}</span>

            @foreach ($calendarEvent as $calendarEventData)
              @php
                $dateFormat = \Carbon\Carbon::parse($calendarEventData->date);
              @endphp
              @if ($startOfCalendar == $dateFormat)
                <span class="flex justify-center items-center w-full h-12 bg-gray-200">Run</span>
              @endif
            @endforeach
          </div>
          @php
            $startOfCalendar->addDay();
          @endphp
        @endwhile
      </div>
    </div>

    {{-- <div id="hs-focus-management-modal"
      class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
      <div
        class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div
          class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
          <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
            <h3 class="font-bold text-gray-800 dark:text-white">
              Modal title
            </h3>
            <button type="button"
              class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700"
              data-hs-overlay="#hs-focus-management-modal">
              <span class="sr-only">Close</span>
              <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M18 6 6 18"></path>
                <path d="m6 6 12 12"></path>
              </svg>
            </button>
          </div>
          <div class="p-4 overflow-y-auto">
            <label for="input-label" class="block text-sm font-medium mb-2 dark:text-white">Email</label>
            <input type="email" id="input-label"
              class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:placeholder-neutral-500 dark:text-neutral-400"
              placeholder="you@site.com" autofocus="">
          </div>
          <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
            <button type="button"
              class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800"
              data-hs-overlay="#hs-focus-management-modal">
              Close
            </button>
            <button type="button"
              class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
              Save changes
            </button>
          </div>
        </div>
      </div>
    </div> --}}
  </div>

  {{-- <div>
    <link rel="dns-prefetch" href="//unpkg.com" />
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net" />
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js" defer></script>

    <style>
      [x-cloak] {
        display: none;
      }
    </style>

    <div class="antialiased sans-serif bg-white-100 h-screen">
      <div x-data="app()" x-init="[initDate(), getNoOfDays()]" x-cloak>
        <div class="container mx-auto px-4 py-2 md:py-24">

          <div class="bg-white rounded-lg shadow overflow-hidden">

            <div class="flex items-center justify-between py-2 px-6">
              <div>
                <span x-text="MONTH_NAMES[month]" class="text-lg font-bold text-gray-800"></span>
                <span x-text="year" class="ml-1 text-lg text-gray-600 font-normal"></span>
              </div>
              <div class="border rounded-lg px-1" style="padding-top: 2px;">
                <button type="button"
                  class="leading-none rounded-lg transition ease-in-out duration-100 inline-flex cursor-pointer hover:bg-gray-200 p-1 items-center"
                  :class="{ 'cursor-not-allowed opacity-25': month == 0 }" :disabled="month == 0 ? true : false"
                  @click="month--; getNoOfDays()">
                  <svg class="h-6 w-6 text-gray-500 inline-flex leading-none" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                  </svg>
                </button>
                <div class="border-r inline-flex h-6"></div>
                <button type="button"
                  class="leading-none rounded-lg transition ease-in-out duration-100 inline-flex items-center cursor-pointer hover:bg-gray-200 p-1"
                  :class="{ 'cursor-not-allowed opacity-25': month == 11 }" :disabled="month == 11 ? true : false"
                  @click="month++; getNoOfDays()">
                  <svg class="h-6 w-6 text-gray-500 inline-flex leading-none" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </button>
              </div>
            </div>

            <div class="-mx-1 -mb-1">
              <div class="flex flex-wrap" style="margin-bottom: -40px;">
                <template x-for="(day, index) in DAYS" :key="index">
                  <div style="width: 14.26%" class="px-2 py-2">
                    <div x-text="day" class="text-gray-600 text-sm uppercase tracking-wide font-bold text-center">
                    </div>
                  </div>
                </template>
              </div>

              <div class="flex flex-wrap border-t border-l">
                <template x-for="blankday in blankdays">
                  <div style="width: 14.28%; height: 120px" class="text-center border-r border-b px-4 pt-2"></div>
                </template>
                <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">
                  <div style="width: 14.28%; height: 120px" class="px-4 pt-2 border-r border-b relative">
                    <div @click="showEventModal(date)" x-text="date"
                      class="inline-flex w-6 h-6 items-center justify-center cursor-pointer text-center leading-none rounded-full transition ease-in-out duration-100"
                      :class="{
                          'bg-blue-500 text-white': isToday(date) == true,
                          'text-gray-700 hover:bg-blue-200': isToday(
                              date) == false
                      }">
                    </div>
                    <div style="height: 80px;" class="overflow-y-auto mt-1">
                      <template
                        x-for="event in events.filter(e => new Date(e.event_date).toDateString() ===  new Date(year, month, date).toDateString() )">
                        <div class="px-2 py-1 rounded-lg mt-1 overflow-hidden border"
                          :class="{
                              'border-blue-200 text-blue-800 bg-blue-100': event.event_theme === 'blue',
                              'border-red-200 text-red-800 bg-red-100': event.event_theme === 'red',
                              'border-yellow-200 text-yellow-800 bg-yellow-100': event.event_theme === 'yellow',
                              'border-green-200 text-green-800 bg-green-100': event.event_theme === 'green',
                              'border-purple-200 text-purple-800 bg-purple-100': event.event_theme === 'purple'
                          }">
                          <p x-text="event.event_title" class="text-sm truncate leading-tight"></p>
                        </div>
                      </template>
                    </div>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal -->
        <div style=" background-color: rgba(0, 0, 0, 0.8)"
          class="fixed z-40 top-0 right-0 left-0 bottom-0 h-full w-full" x-show.transition.opacity="openEventModal">
          <div class="p-4 max-w-xl mx-auto relative absolute left-0 right-0 overflow-hidden mt-24">
            <div
              class="shadow absolute right-0 top-0 w-10 h-10 rounded-full bg-white text-gray-500 hover:text-gray-800 inline-flex items-center justify-center cursor-pointer"
              x-on:click="openEventModal = !openEventModal">
              <svg class="fill-current w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                  d="M16.192 6.344L11.949 10.586 7.707 6.344 6.293 7.758 10.535 12 6.293 16.242 7.707 17.656 11.949 13.414 16.192 17.656 17.606 16.242 13.364 12 17.606 7.758z" />
              </svg>
            </div>

            <div class="shadow w-full rounded-lg bg-white overflow-hidden w-full block p-8">

              <h2 class="font-bold text-2xl mb-6 text-gray-800 border-b pb-2">Add Event Details</h2>

              <div class="mb-4">
                <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">Event title</label>
                <input
                  class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                  type="text" x-model="event_title">
              </div>

              <div class="mb-4">
                <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">Event date</label>
                <input
                  class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                  type="text" x-model="event_date" readonly>
              </div>

              <div class="inline-block w-64 mb-4">
                <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">Select a theme</label>
                <div class="relative">
                  <select @change="event_theme = $event.target.value;" x-model="event_theme"
                    class="block appearance-none w-full bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700">
                    <template x-for="(theme, index) in themes">
                      <option :value="theme.value" x-text="theme.label"></option>
                    </template>

                  </select>
                  <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                      <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                    </svg>
                  </div>
                </div>
              </div>

              <div class="mt-8 text-right">
                <button type="button"
                  class="bg-white hover:bg-gray-100 text-gray-700 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm mr-2"
                  @click="openEventModal = !openEventModal">
                  Cancel
                </button>
                <button type="button"
                  class="bg-gray-800 hover:bg-gray-700 text-white font-semibold py-2 px-4 border border-gray-700 rounded-lg shadow-sm"
                  @click="addEvent()">
                  Save Event
                </button>
              </div>
            </div>
          </div>
        </div>
        <!-- /Modal -->
      </div>

      <script>
        const MONTH_NAMES = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October',
          'November', 'December'
        ];
        const DAYS = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

        function app() {
          return {
            month: '',
            year: '',
            no_of_days: [],
            blankdays: [],
            days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],

            events: [{
                event_date: new Date(2020, 3, 1),
                event_title: "April Fool's Day",
                event_theme: 'blue'
              },

              {
                event_date: new Date(2020, 3, 10),
                event_title: "Birthday",
                event_theme: 'red'
              },

              {
                event_date: new Date(2020, 3, 16),
                event_title: "Upcoming Event",
                event_theme: 'green'
              }
            ],
            event_title: '',
            event_date: '',
            event_theme: 'blue',

            themes: [{
                value: "blue",
                label: "Blue Theme"
              },
              {
                value: "red",
                label: "Red Theme"
              },
              {
                value: "yellow",
                label: "Yellow Theme"
              },
              {
                value: "green",
                label: "Green Theme"
              },
              {
                value: "purple",
                label: "Purple Theme"
              }
            ],

            openEventModal: false,

            initDate() {
              let today = new Date();
              this.month = today.getMonth();
              this.year = today.getFullYear();
              this.datepickerValue = new Date(this.year, this.month, today.getDate()).toDateString();
            },

            isToday(date) {
              const today = new Date();
              const d = new Date(this.year, this.month, date);

              return today.toDateString() === d.toDateString() ? true : false;
            },

            showEventModal(date) {
              // open the modal
              this.openEventModal = true;
              this.event_date = new Date(this.year, this.month, date).toDateString();
            },

            addEvent() {
              if (this.event_title == '') {
                return;
              }

              this.events.push({
                event_date: this.event_date,
                event_title: this.event_title,
                event_theme: this.event_theme
              });

              console.log(this.events);

              // clear the form data
              this.event_title = '';
              this.event_date = '';
              this.event_theme = 'blue';

              //close the modal
              this.openEventModal = false;
            },

            getNoOfDays() {
              let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();

              // find where to start calendar day of week
              let dayOfWeek = new Date(this.year, this.month).getDay();
              let blankdaysArray = [];
              for (var i = 1; i <= dayOfWeek; i++) {
                blankdaysArray.push(i);
              }

              let daysArray = [];
              for (var i = 1; i <= daysInMonth; i++) {
                daysArray.push(i);
              }

              this.blankdays = blankdaysArray;
              this.no_of_days = daysArray;
            }
          }
        }
      </script>
    </div>
  </div> --}}

</x-app-layout>
