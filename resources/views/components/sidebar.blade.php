<nav class="sidebar">
    <div class="sidebar-content">
        <ul>
            <li class="sidebar-item">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="navlink-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-speedometer" viewBox="0 0 16 16">
                    <path d="M8 2a.5.5 0 0 1 .5.5V4a.5.5 0 0 1-1 0V2.5A.5.5 0 0 1 8 2M3.732 3.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707M2 8a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 8m9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5m.754-4.246a.39.39 0 0 0-.527-.02L7.547 7.31A.91.91 0 1 0 8.85 8.569l3.434-4.297a.39.39 0 0 0-.029-.518z"/>
                    <path fill-rule="evenodd" d="M6.664 15.889A8 8 0 1 1 9.336.11a8 8 0 0 1-2.672 15.78zm-4.665-4.283A11.95 11.95 0 0 1 8 10c2.186 0 4.236.585 6.001 1.606a7 7 0 1 0-12.002 0"/>
                    </svg>
                    </span>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a href="{{ route('editais.index') }}" class="{{ request()->routeIs('editais.*') ? 'active' : '' }}">
                    <span class="navlink-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
                        <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z"/>
                    </svg>
                    </span>
                    <span>Editais</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a href="{{ route('analise-inscricoes.index') }}" class="{{ request()->routeIs('analise-inscricoes.*') ? 'active' : '' }}">
                    <span class="navlink-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-check-fill" viewBox="0 0 16 16">
                    <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m1.354 4.354-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708.708"/>
                    </svg> 
                    </span>
                    <span>Análise de inscrições</span>
                </a>
            </li>

            <li class="sidebar-item">
                <div href="#" class="sidebar-dropdown {{ request()->routeIs('pos.*') ? 'show-subitems' : '' }}">
                    <div>
                        <span class="navlink-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-mortarboard-fill" viewBox="0 0 16 16">
                                <path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917z"/>
                                <path d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466z"/>
                            </svg>
                        </span>
                        <span>Pós-Graduação</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" id="dropdown-icon" fill="none" stroke="currentColor" stroke-width="0.8" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>
                    </svg> 
                </div>

                <div class="sidebar-dropdown-subitems">
                    <a href="{{ route('pos.programas.index') }}" class="{{ request()->routeIs('pos.programas.*') ? 'active' : '' }}">Programas</a>
                    <a href="{{ route('pos.cursos.index') }}" class="{{ request()->routeIs('pos.cursos.index') ? 'active' : '' }}">Cursos</a>
                    <a href="{{ route('pos.areas-concentracao.index') }}" class="{{ request()->routeIs('pos.areas-concentracao.index') ? 'active' : '' }}">Áreas de Concentração</a>
                    <a href="{{ route('pos.linhas-pesquisa.index') }}" class="{{ request()->routeIs('pos.linhas-pesquisa.index') ? 'active' : '' }}">Linhas de Pesquisa</a>
                    <a href="{{ route('pos.sublinhas.index') }}" class="{{ request()->routeIs('pos.sublinhas.index') ? 'active' : '' }}">Sublinhas</a>
                    <a href="{{ route('pos.disciplinas-aluno-externo.index') }}" class="{{ request()->routeIs('pos.disciplinas-aluno-externo.index') ? 'active' : '' }}">Disciplinas de aluno externo</a>
                </div>
            </li>
    
            <li class="sidebar-item">
                <a href="{{ route('professores.index') }}" class="{{ request()->routeIs('professores.*') ? 'active' : '' }}">
                    <span class="navlink-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                    </svg>
                    </span>
                    <span>Professores</span>
                </a>
            </li>
        </ul>
    </div>
</nav>