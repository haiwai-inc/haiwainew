<template>
  <component
    class="profile-dropdown"
    :is="tag"
    :class="[
      { show: isOpen },
      { dropdown: direction === 'down' },
      { dropup: direction === 'up' }
    ]"
    aria-haspopup="true"
    :aria-expanded="isOpen"
    @click="toggleDropDown"
    v-click-outside="closeDropDown"
  >
    <slot name="title">
      <a
        class="dropdown-toggle nav-link"
        :class="{ 'no-caret': hideArrow }"
        data-toggle="dropdown"
      >
        <img
          v-lazy="avatarurl"
          alt="Circle Image"
          class="rounded-circle avatar"
        />
        
        <span class="no-icon">{{ username }}</span>
      </a>
    </slot>
    <ul
      class="dropdown-menu"
      :class="[
        { 'dropdown-menu-right': position === 'right' },
        { show: isOpen }
      ]"
    >
      <slot></slot>
    </ul>
  </component>
</template>
<script>
export default {
  name: 'profile-drop-down',
  props: {
    direction: {
      type: String,
      default: 'down'
    },
    username: String,
    avatarurl: String,
    position: String,
    hideArrow: Boolean,
    tag: {
      type: String,
      default: 'li'
    }
  },
  data() {
    return {
      isOpen: false
    };
  },
  provide() {
    return {
      closeDropDown: this.closeDropDown
    }
  },
  methods: {
    toggleDropDown() {
      this.isOpen = !this.isOpen;
      this.$emit('change', this.isOpen);
    },
    closeDropDown() {
      this.isOpen = false;
      this.$emit('change', this.isOpen);
    }
  }
};
</script>
<style>
.dropdown {
  list-style-type: none;
}

.dropdown .dropdown-toggle {
  cursor: pointer;
}
.avatar{
  width: 30px;
  height:30px;
  margin-right: 8px;
}
</style>
