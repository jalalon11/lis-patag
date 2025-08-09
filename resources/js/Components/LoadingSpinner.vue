<template>
  <div 
    v-if="show" 
    class="loading-spinner-overlay" 
    :class="{ 'overlay': overlay, 'inline': !overlay }"
  >
    <div class="loading-spinner-container">
      <div 
        aria-live="assertive" 
        role="alert" 
        class="loader"
        :style="{ 
          '--size': size + 'px', 
          '--hue': hue,
          '--speed': speed + 's',
          '--border': border + 'px'
        }"
      ></div>
      <div v-if="text" class="loading-text">{{ text }}</div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LoadingSpinner',
  props: {
    show: {
      type: Boolean,
      default: true
    },
    overlay: {
      type: Boolean,
      default: false
    },
    size: {
      type: Number,
      default: 60
    },
    border: {
      type: Number,
      default: 6
    },
    hue: {
      type: Number,
      default: 210
    },
    speed: {
      type: Number,
      default: 1
    },
    text: {
      type: String,
      default: ''
    }
  }
}
</script>

<style scoped>
.loading-spinner-overlay {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
}

.loading-spinner-overlay.overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(2px);
  z-index: 9999;
}

.loading-spinner-overlay.inline {
  position: relative;
  padding: 2rem;
}

.loading-spinner-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  position: relative;
}

.loader {
  --hue: 210;
  --size: 60px;
  --border: 6px;
  --speed: 1s;
  --blur: var(--border);
}

.loader {
  width: var(--border);
  aspect-ratio: 1;
  background: white;
  border-radius: 50%;
  position: relative;
  --y: calc((var(--size) * -0.5) + (var(--border) * 0.5));
  transform: rotate(0deg) translateY(var(--y));
  animation: spin var(--speed) infinite linear;
}

.loader::before {
  content: "";
  position: absolute;
  inset: calc(var(--border) * -0.5);
  border-radius: 50%;
  background: white;
  filter: blur(var(--blur));
  z-index: -1;
}

.loader::after {
  content: "";
  width: var(--size);
  aspect-ratio: 1;
  position: absolute;
  top: 0%;
  left: 50%;
  translate: -50% 0;
  background: conic-gradient(
    white,
    hsl(var(--hue), 100%, 70%),
    hsl(var(--hue), 100%, 10%),
    transparent 65%
  );
  border-radius: 50%;
  mask: radial-gradient(
    transparent calc(((var(--size) * 0.5) - var(--border)) - 1px),
    white calc((var(--size) * 0.5) - var(--border))
  );
}

.loading-text {
  margin-top: 1rem;
  color: var(--primary-color, #2c5aa0);
  font-weight: 600;
  font-size: 0.875rem;
  text-align: center;
}

@keyframes spin {
  to {
    transform: rotate(-360deg) translateY(var(--y));
  }
}

/* Dark theme support */
@media (prefers-color-scheme: dark) {
  .loading-spinner-overlay.overlay {
    background-color: rgba(0, 0, 0, 0.9);
  }
  
  .loader {
    background: #333;
  }
  
  .loader::before {
    background: #333;
  }
  
  .loading-text {
    color: #fff;
  }
}
</style>
