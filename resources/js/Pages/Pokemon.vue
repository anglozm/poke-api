<script setup>
import {Head, useForm} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Dropdown from "@/Components/Dropdown.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import {nextTick, onMounted, ref} from "vue";
import axios from 'axios';
import DangerButton from "@/Components/DangerButton.vue";

const pokeId = ref(0);
const pokemon = ref([]);
const pokemonType = ref([]);

const emptyPokeList = ref(false);

const nameInput = ref([]);

const confirmingPokemonCreate = ref(false);
const confirmingPokemonUpdate = ref(false);
const confirmingPokemonDelete = ref(false);

const form = useForm({
    name: '',
    type: '',
    photo: '',
});

const getPokemonList = async () => {
    try {
        const res = await axios.get('/api/pokemon');
        const results = sortByName(res.data.data);
        console.log('Pokemon', results);

        return results;
    } catch (error) {
        console.error('Error getting pokemon', error);

        throw error;
    }
}

const fetchPokemonTypeList = async () => {
    try {
        const res = await axios.get('https://pokeapi.co/api/v2/type');
        const results = sortByName(res.data.results);
        console.log('Pokemon types', results);

        return results;
    } catch (error) {
        console.error('Error getting pokemon', error);

        throw error;
    }
}

const fetchPokeImageByName = async () => {
    const url = 'https://pokeapi.co/api/v2/pokemon/' + form.name.toLowerCase();
    console.log(url);

    try {
        const poke = await axios.get(url);
        const pokeName = poke.data.name;
        const pokeImage = poke.data.sprites.other.dream_world.front_default;
        console.log('Fetch "' + pokeName + '" by name:', pokeImage);

        return pokeImage
    } catch (error) {
        console.error('Error fetching pokemon. Pokemon not found by name', error);

        throw error;
    }
}

onMounted(async () => {
    pokemon.value = await getPokemonList();
    pokemonType.value = await fetchPokemonTypeList();
    emptyPokeList.value = pokemon.value.length !== 0;
})

//========================================================================
const sortByName = (arr) => arr.sort((a, b) => (a.name > b.name) ? 1 : -1);

//========================================================================
const confirmPokemonCreate = () => {
    confirmingPokemonCreate.value = true;
    nextTick(() => nameInput.value.focus());
    console.log('On create confirm...');
};

const confirmPokemonUpdate = (poke) => {
    confirmingPokemonUpdate.value = true;
    nextTick(() => nameInput.value.focus());
    pokeId.value = poke.id;
    console.log('On update confirm... Id:', pokeId.value);
};

const confirmPokemonDelete = (poke) => {
    confirmingPokemonDelete.value = true;
    pokeId.value = poke.id;
    console.log('On delete confirm... Id:', pokeId.value);
};
//========================================================================
const submitPokemonCreate = async () => {
    try {
        form.photo = await fetchPokeImageByName();
        form.post(route('pokemon.store'), {
            preserveScroll: true,
            onSuccess: () => closeModalCreate(),
        });

        pokemon.value = await getPokemonList();
        emptyPokeList.value = pokemon.value.length !== 0;

        console.log('... Pokemon Created');
    } catch (error) {
        console.error(error);
    }
};

const submitPokemonUpdate = async () => {
    try {
        form.photo = await fetchPokeImageByName();
        form.patch(route('pokemon.update', pokeId.value), {
            preserveScroll: true,
            onSuccess: () => closeModalUpdate(),
        });

        pokemon.value = await getPokemonList();
        emptyPokeList.value = pokemon.value.length !== 0;

        console.log('... Update confirmed. Id:', pokeId.value);
    } catch (error) {
        console.error(error);
    }
};

const submitPokemonDelete = async () => {
    try {
        form.delete(route('pokemon.destroy', pokeId.value), {
            preserveScroll: true,
            onSuccess: () => closeModalDelete(),
        });

        pokemon.value = await getPokemonList();
        emptyPokeList.value = pokemon.value.length !== 0;

        console.log('... Delete confirmed. Id:', pokeId.value);
    } catch (error) {
        console.error(error);
    }
};
//========================================================================
const closeModalCreate = async () => {
    confirmingPokemonCreate.value = false;
    form.reset();
};

const closeModalUpdate = () => {
    confirmingPokemonUpdate.value = false;
    form.reset();
};

const closeModalDelete = () => {
    confirmingPokemonDelete.value = false;
    form.reset();
};
</script>

<template>
    <Head title="Pokemon" />

    <AuthenticatedLayout>
        <div class="flex items-center justify-center pt-4">
            <section class="flex justify-between px-4 w-[800px] rounded-md shadow-md hover:shadow-lg bg-gray-50">
                <h2 class="flex items-center py-4 font-semibold text-xl text-gray-800 leading-tight">Poke List</h2>
                <div class="flex py-4">
                    <PrimaryButton class="ml-4" @click="confirmPokemonCreate()">
                        Add Pokemon
                    </PrimaryButton>
                </div>
            </section>
        </div>

        <div class="flex items-center justify-center pb-10">
            <section v-if="emptyPokeList" class="flex justify-between px-4 m-2 pb-2 w-[800px] rounded-md shadow-md hover:shadow-lg bg-gray-200">
                <ul class="py-2">
                    <li v-for="(poke, index) in pokemon" :key="index" class="motion-safe:hover:scale-[1.01] rounded-md mt-2 bg-gray-50">
                        <section class="flex justify-between w-[770px] rounded-md shadow-md hover:shadow-2xl">
                            <img :src="poke.photo" alt="Pokemon logo" class="flex-none w-20 h-20 m-6 bg-gray-50"/>
                            <div class="flex-auto self-center m-1">
                                <ul class="py-4">
                                    <li>
                                        <b>{{ poke.name }}</b>
                                    </li>
                                    <li>
                                        <i>{{ poke.type }}</i>
                                    </li>
                                </ul>
                            </div>
                            <div class="pt-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex">
                                            <button class="flex">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <button
                                            @click="confirmPokemonUpdate(poke)"
                                            class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            @click="confirmPokemonDelete(poke)"
                                            class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                        >
                                            Delete
                                        </button>
                                    </template>
                                </Dropdown>
                            </div>
                        </section>
                    </li>
                </ul>
            </section>
            <label v-else class="flex items-center py-60 font-semibold text-xl text-gray-800 leading-tight">
                Empty Pokemon list
            </label>
        </div>

        <Modal :show="confirmingPokemonCreate" @close="closeModalCreate">
            <form @submit.prevent="submitPokemonCreate()" class="m-6">
                <div class="mt-10">
                    <InputLabel for="name" value="Name" />

                    <TextInput
                        id="name"
                        ref="nameInput"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        autofocus
                        autocomplete="name"
                        placeholder="Poke name"
                    />

                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="type" value="Type" />

                    <select
                        id="type"
                        name="types"
                        class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1"
                        v-model="form.type"
                    >
                        <option
                            v-for="type in pokemonType"
                            :key="type"
                            :value="type.name"
                            class="block font-medium text-sm text-gray-700"
                        >
                            {{ type.name }}
                        </option>
                    </select>
                </div>

                <div class="flex items-center justify-end pt-8 pb-4">
                    <SecondaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="closeModalCreate">
                        Cancel
                    </SecondaryButton>

                    <PrimaryButton class="ml-4">
                        Create
                    </PrimaryButton>
                </div>
            </form>
        </Modal>

        <Modal :show="confirmingPokemonUpdate" @close="closeModalUpdate">
            <form @submit.prevent="submitPokemonUpdate()" class="m-6">
                <div class="mt-10">
                    <InputLabel for="name" value="Name" />

                    <TextInput
                        id="name"
                        ref="nameInput"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        autofocus
                        autocomplete="name"
                        placeholder="Poke name"
                    />

                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="type" value="Type" />

                    <select
                        id="type"
                        name="types"
                        class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1"
                        v-model="form.type"
                    >
                        <option
                            v-for="type in pokemonType"
                            :key="type"
                            :value="type.name"
                            class="block font-medium text-sm text-gray-700"
                        >
                            {{ type.name }}
                        </option>
                    </select>
                </div>

                <div class="flex items-center justify-end pt-8 pb-4">
                    <SecondaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="closeModalUpdate">
                        Cancel
                    </SecondaryButton>

                    <PrimaryButton class="ml-4">
                        Edit
                    </PrimaryButton>
                </div>
            </form>
        </Modal>

        <Modal :show="confirmingPokemonDelete" @close="closeModalDelete">
            <h3 class="flex items-center p-10 font-semibold text-xl text-gray-800 leading-tight">
                Are you sure you want to delete this pokemon?
            </h3>
            <div class="flex items-center justify-end p-4">
                <SecondaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="closeModalDelete">
                    Cancel
                </SecondaryButton>

                <DangerButton class="ml-4" @click="submitPokemonDelete()">
                    Delete
                </DangerButton>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
