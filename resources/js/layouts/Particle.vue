<template>
    <div id="particle-container">
	<div  v-for="(index) in particles" :key="index" class="particle"></div>
</div>
</template>

<script>
export default {
    data(){
        return {
            particles: 400,
        }
    }

}
</script>

<style lang="scss" >

.particle {
	position: absolute;
 	border-radius: 50%;
}

@for $i from 1 through 30 {
	@keyframes particle-animation-#{$i} {
		100% {
			transform: translate3d((random(90) * 1vw), (random(90) * 1vh), (random(100) * 1px));
		}
	}

	.particle:nth-child(#{$i}){
		animation: particle-animation-#{$i} 60s infinite;
		$size: random(5) + 5 + px;
		opacity: calc(random(100)/100) ;
		height: $size;
		width: $size;
		animation-delay: -$i * .2s;
		transform: translate3d((random(90) * 1vw), (random(90) * 1vh), (random(100) * 1px));
		background: hsl(random(360), 70%, 50%);
	}
}
</style>
