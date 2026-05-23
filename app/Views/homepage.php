<?php
use App\Helpers\Sessao;

?>

<!-- ======= Hero Section ======= -->
<!-- content -->
<header id="home">
    <div class="wrapper">
        <div class="col-a">
            <h4 style="text-transform: uppercase">BOAS-VINDAS A Clínica AGOSMIL </h4>
            <h1>Assistência médica simplificada para todos</h1>
            <p>
                Os médicos da ClínicaAGOSMIL vão além dos sintomas para tratar a causa
                raiz de sua doença e proporcionar uma cura a longo prazo.
            </p>
            <a href="<?=URL?>\do" class="button">
            Agende Sua Consulta
            </a>
        </div>
        <div class="col-b">
            <img
                src="<?= asset('assets/images/dotora.png') ?>"
                alt="Mulher negra vestindo moletom verde e sorrindo" />
        </div>
        <div class="stats">
            <div class="stat">
                <h3 id="contador1">...</h3>
                <p>Pacientes atendidos</p>
            </div>
            <div class="stat">
                <h3 id="contador2">...</h3>
                <p>Especialistas disponíveis</p>
            </div>
            <div class="stat">
                <h3 id="contador3">...</h3>
                <p>Anos no mercado</p>
            </div>
        </div>
    </div>
</header>

<section id="services">
    <div class="wrapper">
        <header>
            <h4>Serviços</h4>
            <h2>Como podemos ajudá-lo a se sentir melhor?</h2>
        </header>
        <div class="cards">
            <div class="card">
                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="12" fill="#DCE9E2" />
                    <path
                        d="M17.091 8.18182L10.091 15.1818L6.90918 12"
                        stroke="#00856F"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <h3>Problemas digestivos</h3>
                <p>
                    O sistema digestivo humano, estendendo-se da boca ao ânus, é um conjunto de órgãos responsável por receber alimentos, decompô-los em nutrientes (digestão), absorver nutrientes e água na corrente sanguínea e eliminar resíduos não digeríveis.
                </p>
            </div>
            <div class="card">
                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="12" fill="#DCE9E2" />
                    <path
                        d="M17.091 8.18182L10.091 15.1818L6.90918 12"
                        stroke="#00856F"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <h3>Saúde Hormonal</h3>
                <p>
                    A saúde hormonal é o equilíbrio das substâncias produzidas pelas glândulas endócrinas, fundamentais para regular metabolismo, humor, sono, reprodução e energia.
                </p>
            </div>
            <div class="card">
                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="12" fill="#DCE9E2" />
                    <path
                        d="M17.091 8.18182L10.091 15.1818L6.90918 12"
                        stroke="#00856F"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <h3>Bem-estar mental</h3>
                <p>
                    O bem-estar mental é um estado de equilíbrio emocional, psicológico e social, no qual o indivíduo reconhece suas capacidades, lida com as tensões da vida, trabalha produtivamente e contribui para sua comunidade. Não é apenas a ausência de doenças, mas a habilidade de gerenciar emoções e enfrentar desafios com resilencia.
                </p>
            </div>
            <div class="card">
                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="12" fill="#DCE9E2" />
                    <path
                        d="M17.091 8.18182L10.091 15.1818L6.90918 12"
                        stroke="#00856F"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <h3>Cuidados Pediátricos</h3>
                <p>
                    Os cuidados pediátricos essenciais envolvem o acompanhamento médico regular (consultas de rotina), a manutenção da carteira de vacinação em dia, nutrição equilibrada, higiene adequada e estimulação do desenvolvimento físico e emocional.
                </p>
            </div>
            <div class="card">
                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="12" fill="#DCE9E2" />
                    <path
                        d="M17.091 8.18182L10.091 15.1818L6.90918 12"
                        stroke="#00856F"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <h3>Equilíbrio Mental</h3>
                <p>
                    Um refúgio para o acolhimento: Oferecemos um espaço seguro e livre de julgamentos, onde sua história é ouvida com respeito. O primeiro passo para o equilíbrio é sentir-se compreendido em um ambiente que prioriza o seu conforto e o sigilo absoluto das suas vivências.
                </p>
            </div>
            <div class="card">
                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="12" fill="#DCE9E2" />
                    <path
                        d="M17.091 8.18182L10.091 15.1818L6.90918 12"
                        stroke="#00856F"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <h3>Saúde do Coração</h3>
                <p>
                    A saúde do coração depende da manutenção do 
                    sistema cardiovascular, envolvendo dieta equilibrada 
                    (rica em fibras, vegetais, proteínas magras), exercí
                    cios físicos regulares, sono de qualidade (7-9h) e 
                    controle de fatores de risco como estresse, tabagismo, 
                    colesterol e pressão arterial. 
                </p>
            </div>
        </div>
    </div>
</section>

<section id="about">
    <div class="wrapper">
        <div class="col-a">
            <header>
                <h4>Sobre nós</h4>
                <h2>Entenda quem somos e por que existimos</h2>
            </header>
            <p>
                Nós somos a clinica AGOSMIL uma clínica bastante conhecida,especialmente por quem atua ou depende do sistema de saúde,
                embora o nome surgiu em uma origem ligada a odontologia mas 
                agora ela expandiu-se muito ao longo dos anos envolvendo
                outras areas.
            </p>
        </div>
        <div class="col-b">
            <img src="<?= asset('assets/images/ivan.png') ?>" alt="Doctor fazendo consulta" />
        </div>
    </div>
        <div class="cards">
            <div class="card">
            <i class="fa-solid fa-user-doctor " style="color: #850077; font-size: x-large;"></i>
                <h3 id="contador4">...</h3>
                <p>Doctores</p>
            </div>
            <div class="card">
            <i class="fa-solid fa-notes-medical  " style="color: #850077; font-size: x-large;"></i>
                <h3 id="contador5">...</h3>
                <p>Departamentos</p>
            </div>
            <div class="card">
            <i class="fa-solid fa-flask-vial " style="color: #850077; font-size: x-large;"></i>
                <h3 id="contador6">...</h3>
                <p>Laboratorios</p>
            </div>
        </div>
</section>

<section id="contacts">
    <div class="wrapper">
        <div class="col-a">
            <h2>Entre em contato com a gente!</h2>
            <ul class="contact">
                <li class="element adress">
                    <svg
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M21 10C21 17 12 23 12 23C12 23 3 17 3 10C3 7.61305 3.94821 5.32387 5.63604 3.63604C7.32387 1.94821 9.61305 1 12 1C14.3869 1 16.6761 1.94821 18.364 3.63604C20.0518 5.32387 21 7.61305 21 10Z"
                            stroke="#00856F"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path
                            d="M12 13C13.6569 13 15 11.6569 15 10C15 8.34315 13.6569 7 12 7C10.3431 7 9 8.34315 9 10C9 11.6569 10.3431 13 12 13Z"
                            stroke="#00856F"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <p>R. Amauri Souza, 346</p>
                </li>
                <li class="element email">
                    <svg
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z"
                            stroke="#00856F"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path
                            d="M22 6L12 13L2 6"
                            stroke="#00856F"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <p>contato@ClinicaAgosmil.com</p>
                </li>
                <a href="<?=URL?>\do" target="_blank" class="button">
                    <!-- <svg
                        width="18"
                        height="18"
                        viewBox="0 0 18 18"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12.8337 10.6667C12.667 10.5833 11.5837 10.0833 11.417 10C11.2503 9.91668 11.0837 9.91668 10.917 10.0833C10.7503 10.25 10.417 10.75 10.2503 10.9167C10.167 11.0833 10.0003 11.0833 9.83366 11C9.25033 10.75 8.66699 10.4167 8.16699 10C7.75033 9.58334 7.33366 9.08334 7.00033 8.58334C6.91699 8.41668 7.00033 8.25001 7.08366 8.16668C7.16699 8.08334 7.25033 7.91668 7.41699 7.83334C7.50033 7.75001 7.58366 7.58334 7.58366 7.50001C7.66699 7.41668 7.66699 7.25001 7.58366 7.16668C7.50033 7.08334 7.08366 6.08334 6.91699 5.66668C6.83366 5.08334 6.66699 5.08334 6.50033 5.08334C6.41699 5.08334 6.25033 5.08334 6.08366 5.08334C5.91699 5.08334 5.66699 5.25001 5.58366 5.33334C5.08366 5.83334 4.83366 6.41668 4.83366 7.08334C4.91699 7.83334 5.16699 8.58334 5.66699 9.25001C6.58366 10.5833 7.75033 11.6667 9.16699 12.3333C9.58366 12.5 9.91699 12.6667 10.3337 12.75C10.7503 12.9167 11.167 12.9167 11.667 12.8333C12.2503 12.75 12.7503 12.3333 13.0837 11.8333C13.2503 11.5 13.2503 11.1667 13.167 10.8333C13.167 10.8333 13.0003 10.75 12.8337 10.6667ZM14.917 3.08334C11.667 -0.166656 6.41699 -0.166656 3.16699 3.08334C0.500325 5.75001 0.000325501 9.83334 1.83366 13.0833L0.666992 17.3333L5.08366 16.1667C6.33366 16.8333 7.66699 17.1667 9.00033 17.1667C13.5837 17.1667 17.2503 13.5 17.2503 8.91668C17.3337 6.75001 16.417 4.66668 14.917 3.08334ZM12.667 14.75C11.5837 15.4167 10.3337 15.8333 9.00033 15.8333C7.75033 15.8333 6.58366 15.5 5.50033 14.9167L5.25033 14.75L2.66699 15.4167L3.33366 12.9167L3.16699 12.6667C1.16699 9.33334 2.16699 5.16668 5.41699 3.08334C8.66699 1.00001 12.8337 2.08334 14.8337 5.25001C16.8337 8.50001 15.917 12.75 12.667 14.75Z"
                            fill="white" />
                    </svg> -->
                    Agende Sua Consulta
                </a>
            </ul>
        </div>
        <div class="col-b">
            <img src="<?= asset('assets/images/man.png') ?>" alt="Homem usando um smartphone" />
        </div>
    </div>
</section>
<!-- content -->