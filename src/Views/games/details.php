<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($gameDetails['name']); ?> - Detalhes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Configuração para estilizar o HTML vindo da API na descrição -->
    <style type="text/tailwindcss">
        .game-description p { @apply mb-4 text-zinc-300 leading-relaxed; }
        .game-description h1, .game-description h2, .game-description h3 { @apply text-white font-bold mt-6 mb-3 uppercase tracking-wide; }
        .game-description ul { @apply list-disc list-inside mb-4 text-zinc-300; }
        .game-description strong { @apply text-white font-bold; }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-200 font-sans min-h-screen pb-12 selection:bg-violet-600 selection:text-white">

    <!-- Header Estilo Launcher -->
    <header class="bg-zinc-900 border-b-4 border-violet-600 shadow-md px-6 py-5 mb-8">
        <div class="max-w-5xl mx-auto flex items-center justify-between gap-4">
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tighter uppercase">Detalhes do Jogo</h1>
            <a href="index.php?action=home" class="bg-zinc-800 hover:bg-zinc-700 text-zinc-300 px-5 py-2.5 rounded-sm font-bold uppercase tracking-wide text-sm border-b-2 border-zinc-950 hover:border-zinc-900 transition-colors shrink-0">Voltar à Biblioteca</a>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-6">
        
        <!-- Bloco Principal: Capa + Título + Sobre -->
        <div class="bg-zinc-900 border-2 border-zinc-800 rounded-sm shadow-xl overflow-hidden mb-10 flex flex-col md:flex-row">
            
            <!-- Coluna da Esquerda (Imagem) -->
            <div class="md:w-1/3 bg-zinc-950 border-b-2 md:border-b-0 md:border-r-2 border-zinc-800 shrink-0">
                <?php if (!empty($imagePath)): ?>
                    <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Capa do jogo <?php echo htmlspecialchars($gameDetails['name']); ?>" class="w-full h-auto object-cover md:h-full">
                <?php else: ?>
                    <div class="w-full h-64 md:h-full flex items-center justify-center text-zinc-700 font-bold uppercase text-lg">Sem Capa</div>
                <?php endif; ?>
            </div>

            <!-- Coluna da Direita (Infos) -->
            <div class="p-6 md:p-8 flex-1 flex flex-col">
                <h1 class="text-3xl sm:text-4xl font-black text-white uppercase tracking-tight mb-6"><?php echo htmlspecialchars($gameDetails['name']); ?></h1>
                
                <h3 class="text-sm font-black text-violet-500 uppercase tracking-widest mb-3">Sobre o jogo</h3>
                
                <div class="relative">
                    <!-- Contêiner do texto com limite de altura (max-h-56 = 14rem/224px) -->
                    <div id="descriptionContainer" class="game-description text-sm sm:text-base pr-2 max-h-56 overflow-hidden transition-[max-height] duration-500 ease-in-out">
                        <?php 
                            // A API do RAWG geralmente retorna HTML na descrição. A tag style acima cuida da formatação.
                            echo $gameDetails['description'] ?: '<p class="text-zinc-500 italic">Nenhuma descrição disponível para este jogo.</p>'; 
                        ?>
                    </div>
                    <!-- Efeito de gradiente esfumaçado para indicar que há mais texto -->
                    <div id="descriptionGradient" class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-zinc-900 to-transparent pointer-events-none transition-opacity duration-300"></div>
                </div>
                
                <!-- Botão Mostrar Mais -->
                <button id="toggleDescriptionBtn" class="mt-4 text-violet-400 font-bold uppercase text-xs tracking-widest hover:text-violet-300 transition-colors flex items-center gap-1.5 focus:outline-none w-max">
                    Mostrar mais <span class="text-base leading-none transition-transform duration-300" id="toggleIcon">↓</span>
                </button>
            </div>
        </div>

        <!-- Seção de Resenha -->
        <div>
            <h2 class="text-2xl font-black text-white uppercase tracking-tight border-l-4 border-violet-500 pl-3 mb-6">Minha Resenha</h2>
            
            <form action="index.php?action=save_review" method="post" class="bg-zinc-900 p-6 sm:p-8 rounded-sm border-2 border-zinc-800 shadow-xl">
                <input type="hidden" name="game_id" value="<?php echo htmlspecialchars($gameId); ?>">
                
                <div class="mb-4">
                    <label for="review" class="sr-only">Escreva sua resenha</label>
                    <textarea 
                        id="review"
                        name="review" 
                        rows="6" 
                        placeholder="Escreva suas anotações, pensamentos ou a sua resenha crítica sobre este jogo..."
                        class="w-full bg-zinc-950 border-2 border-zinc-800 text-white rounded-sm px-5 py-4 focus:outline-none focus:border-violet-500 font-medium placeholder-zinc-600 resize-y"
                    ><?php echo isset($userGameInfo['review']) ? htmlspecialchars($userGameInfo['review']) : ''; ?></textarea>
                </div>
                
                <div class="flex justify-end mt-2">
                    <button type="submit" class="w-full sm:w-auto bg-violet-600 hover:bg-violet-500 text-white font-black uppercase tracking-wider py-4 px-10 rounded-sm transition-colors shadow-lg flex items-center justify-center gap-2 text-sm">
                        <span>💾</span> Salvar Resenha
                    </button>
                </div>
            </form>
        </div>

    </main>

    <script src="./assets/js/details.js"></script>
</body>
</html>