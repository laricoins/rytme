<?php
   defined('ABSPATH') || die('');
?>   
<div id="my-app">
  <h1>Vue Select - Multiple options</h1>
  <form>
    <v-select multiple :options="countries" label="country">
      <template #option="option">
        <span><img :src="option.image" />{{ option.country }}</span>
      </template>
    </v-select>
  </form>
</div>

