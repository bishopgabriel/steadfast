<template>
  <div class="flex flex-col items-center justify-center h-screen">
    <div class="max-w-md w-full">
      <h1 class="font-bold mb-8 text-center">Vote an Option</h1>
      <div class="flex justify-between itemsOption">
        <div
          v-for="(option, index) in options"
          :key="index"
          class="itemSingle flex items-center justify-center w-20 h-20 rounded-lg shadow-md cursor-pointer hover:bg-blue-500 hover:text-white transition-colors duration-300"
          :class="{ 'bg-blue-500 text-white': selectedIndex === index }"
          @click="selectOption(index)"
        >
          <img
            :src="option.image"
            alt="Option"
            class=""
          />
        </div>
      </div>
      <div class="flex flex-col">
        <!-- email input -->
        <div class="reginput active_">
        <input
          v-model="email"
          type="email"
          placeholder="Enter your email"
          class="mb-4 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
          :class="{ 'border-red-500': !isEmailValid }"
        />

        <button
          @click="submit"
          class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors duration-300"
        >
          Submit
        </button>
        </div>

        <!-- verification -->
        <div class="verifyinput d-none">          
          <div>
            <input
              v-model="verifycode"
              type="text"
              placeholder="Enter verification code"
              class="mb-4 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />

            <button
              @click="verifybtn"
              class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors duration-300"
            >
              PROCEED
            </button>
          </div>
        </div>
        
        <p class="mb-4">{{ notifier }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HomePage',
  data() {
    return {
      // options: ['Option 1', 'Option 2', 'Option 3', 'Option 4'],
      options: [
        { name: 'Drinks', image: 'https://cdn-icons-png.flaticon.com/512/14037/14037068.png' },
        { name: 'Games', image: 'https://cdn-icons-png.flaticon.com/512/808/808439.png' },
        { name: 'Travel', image: 'https://cdn-icons-png.flaticon.com/512/854/854894.png' },
        { name: 'Party', image: 'https://cdn-icons-png.flaticon.com/512/10531/10531423.png' },
      ],
      selectedOption: null,
      selectedIndex: null,
      email: '',      
      verifycode: '',
      api_key: 'fde36d7693df41436cb0034aeea4d94b',
      baseUrl: 'http://localhost:8000/api/',
      notifier: '',
      voted: false,
    };
  },
  computed: {
    isEmailValid() {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(this.email);
    },
  },
  methods: {
    selectOption(index) {
      this.selectedIndex = index;
      this.selectedOption = this.options[index].name;
    },
    switchInput(){
      document.querySelector('.verifyinput').classList.remove('d-none');
      document.querySelector('.reginput').classList.add('d-none');
    },
    async submit() {
      if(this.email.length<2 || !this.isEmailValid) {this.notifier="Enter valid email"; return}
      if(!this.selectedOption) { this.notifier="Select option"; return}

      let data_ = {
        email: this.email,
      }

      console.log(data_);

      this.getdata(data_, 'register')
      .then(res => {
          // Handle successful response
          console.log(res);
          this.notifier=res.message;
          if('status' in res && res.status=="success") this.switchInput();           
      }).catch(error => {
          // Handle errors
      });

      
    },
    verifybtn(){
      let data_ = {
        email: this.email,
        item: this.selectedOption,
        verifycode: this.verifycode
      }
      console.log(data_)

      this.getdata(data_, 'vote')
      .then(res => {
          // Handle successful response
          console.log(res);
          this.notifier=res.message;
          if('status' in res && res.status=="success") document.querySelector('.verifyinput div').classList.add('d-none');
      }).catch(error => {
          // Handle errors
      });
    },
    async getdata(data, apiurl_) {

      try {
        let response = await fetch(this.baseUrl+apiurl_, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'x-api-key': this.api_key
          },
          body: JSON.stringify(data)
        });
        
        let jsonData = await response.json();
        return jsonData;

      } catch (error) {
        return error;
      }

    },

  },
};
</script>


<style>
.itemsOption{
  display: flex;
  margin-bottom:2rem;
}
.itemsOption img {
    width: 100%;
    padding: 20px;
}
/* .verifyinput {
  display:none;
} */
.itemSingle{
  margin: 10px;
}
.active_ {
  display: block !important;
}
.d-none{
  display: none !important;
}
</style>