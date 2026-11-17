<x-mesero-layout>
    <x-slot name="header">
        <div class="order-page-heading">
            <div>
                <p class="eyebrow">{{ $pedido ? 'ATENCIÓN EN CURSO' : 'NUEVA ATENCIÓN' }}</p>
                <h2 class="order-page-title">Mesa <span>#</span>{{ $mesa->numero }}</h2>
                <p class="order-page-subtitle">
                    <span>{{ ucfirst($mesa->zona) }}</span>
                    <span aria-hidden="true">·</span>
                    <span>{{ $pedido ? 'Agrega productos a la cuenta de esta mesa.' : 'Arma el pedido y envíalo al administrador.' }}</span>
                </p>
            </div>
            <div class="order-table-badge">
                <span class="order-table-badge-icon"><x-icon name="plate" /></span>
                <span><strong>Mesa {{ $mesa->numero }}</strong><small>{{ $pedido ? 'Pedido activo' : 'Lista para atender' }}</small></span>
            </div>
        </div>
    </x-slot>

    <form
        @submit.prevent="submit()"
        data-live-order="{{ $pedido?->id }}"
        class="max-w-7xl mx-auto px-4 sm:px-6 py-6 waiter-order-page"
        x-data="waiterOrder(@js($productos), @js($componentesMixto), @js(route('admin.mesero.pedidos.store', $mesa)), @js(route('admin.mesero.pedidos.index')), {{ (float) ($pedido?->total ?? 0) }}, {{ $mesa->id }}, @js($pedido ? app(\App\Services\RestaurantSnapshot::class)->order($pedido) : null))"
    >
        <div class="order-page-toolbar">
            <a href="{{ route('admin.mesero.pedidos.index') }}" class="order-back-link">
                <span aria-hidden="true">←</span> Volver a mesas
            </a>
            <p class="order-toolbar-hint"><span class="order-hint-dot"></span> Los cambios se guardan al enviar el pedido</p>
        </div>

        <ol class="order-flow" aria-label="Pasos para completar el pedido">
            <li class="order-flow-step"><span class="order-flow-number">01</span><span><strong>Elige</strong><small>Explora la carta</small></span></li>
            <li class="order-flow-step"><span class="order-flow-number">02</span><span><strong>Personaliza</strong><small>Mixtos e indicaciones</small></span></li>
            <li class="order-flow-step"><span class="order-flow-number">03</span><span><strong>Revisa</strong><small>Confirma el resumen</small></span></li>
            <li class="order-flow-step"><span class="order-flow-number">04</span><span><strong>Envía</strong><small>Al administrador</small></span></li>
        </ol>

        <x-flash-messages />

        <div x-show="blocked" x-cloak role="alert" class="order-blocked-alert">
            <div>
                <strong>Esta cuenta ya no está activa</strong>
                <p class="text-sm mt-1" x-text="error"></p>
                <p class="text-xs mt-2">La selección que estás escribiendo se conserva en esta pantalla.</p>
            </div>
            <a href="{{ route('admin.mesero.pedidos.index') }}" class="ui-button secondary">Volver a mesas</a>
        </div>

        <details class="panel order-registered-card mb-6" x-show="registeredOrder" @if (! $pedido) x-cloak @endif>
            <summary>
                <span class="order-registered-icon"><x-icon name="check" /></span>
                <span class="order-registered-copy">
                    <strong>Pedido activo #<span x-text="registeredOrder?.id">{{ $pedido?->id }}</span></strong>
                    <small><span x-text="money(registeredOrder?.total)">S/ {{ number_format($pedido?->total ?? 0, 2) }}</span> · Toca para ver los productos enviados</small>
                </span>
                <span class="status-badge" :class="'status-' + registeredOrder?.estado" x-text="$store.restaurant.stateLabel(registeredOrder?.estado)"></span>
            </summary>
            <div class="order-registered-details">
                <template x-for="detail in registeredOrder?.detalles || []" :key="detail.id">
                    <div class="order-registered-line">
                        <div>
                            <strong x-text="detail.nombre"></strong>
                            <p class="text-xs text-gray-500 mt-1" x-show="detail.componentes" x-text="detail.componentes"></p>
                            <p class="text-xs text-amber-800 mt-2" x-show="detail.observaciones" x-text="detail.observaciones"></p>
                        </div>
                        <span class="font-semibold whitespace-nowrap" x-text="money(detail.precio)"></span>
                    </div>
                </template>
            </div>
        </details>

        <div class="waiter-order-grid">
            <section class="catalog-section" aria-labelledby="catalog-title">
                <div class="catalog-heading">
                    <span class="stat-icon"><x-icon name="plate" /></span>
                    <div>
                        <p class="eyebrow">CARTA DEL RESTAURANTE</p>
                        <h3 id="catalog-title" class="text-xl font-bold mt-1">¿Qué desea el cliente?</h3>
                        <p class="text-sm text-gray-500 mt-1">Busca un producto o explora por categoría.</p>
                    </div>
                </div>

                <div class="catalog-search">
                    <x-icon name="search" />
                    <label for="buscar-pedido" class="sr-only">Buscar productos</label>
                    <input
                        id="buscar-pedido"
                        type="search"
                        x-model="search"
                        @keydown.escape="search = ''"
                        class="search-input"
                        placeholder="Ej. bebida, plato, mixto…"
                        autocomplete="off"
                    >
                    <button x-show="search" x-cloak type="button" class="catalog-search-clear" @click="search = ''" aria-label="Limpiar búsqueda">×</button>
                </div>
                <p class="catalog-result-count" aria-live="polite"><strong x-text="visibleCount"></strong> productos disponibles</p>

                <div class="catalog-categories" role="group" aria-label="Filtrar por categoría">
                    <button type="button" class="filter-chip" :class="{ selected: category === null }" :aria-pressed="category === null" @click="category = null">Todos</button>
                    @foreach ($categorias as $categoria)
                        <button type="button" class="filter-chip" :class="{ selected: category === {{ $categoria->id }} }" :aria-pressed="category === {{ $categoria->id }}" @click="category = {{ $categoria->id }}">{{ $categoria->nombre }}</button>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" aria-live="polite">
                    @foreach ($productos as $producto)
                        <article class="product-tile waiter-product-card {{ $producto->personalizable ? 'sm:col-span-2 mixto-tile' : '' }}" x-show="matches({{ $producto->id }})">
                            <div class="waiter-product-heading">
                                <div>
                                    <p class="eyebrow">{{ $producto->categoria->nombre }}</p>
                                    <h4 class="text-lg font-bold mt-1">{{ $producto->nombre }}</h4>
                                </div>
                                <span class="waiter-product-icon" aria-hidden="true"><x-icon name="{{ $producto->personalizable ? 'plate' : 'orders' }}" /></span>
                            </div>
                            @if ($producto->personalizable)
                                <x-mixto-options :producto="$producto" :componentes="$componentesMixto" />
                            @else
                                <div class="waiter-product-footer">
                                    <span class="waiter-product-price">S/ {{ number_format($producto->precio, 2) }}</span>
                                    <button type="button" class="ui-button !px-4 !text-sm" @click="add({{ $producto->id }})" aria-label="Agregar {{ $producto->nombre }}">
                                        <x-icon name="plus" /> Agregar
                                    </button>
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>

                <div class="panel empty-state catalog-empty-state mt-4" x-show="visibleCount === 0" x-cloak>
                    <x-icon name="search" />
                    <strong>No encontramos productos</strong>
                    <p class="text-sm">Prueba con otro nombre o selecciona una categoría diferente.</p>
                    <button type="button" class="catalog-reset-button" @click="search = ''; category = null">Mostrar toda la carta</button>
                </div>
            </section>

            <aside id="resumen-pedido" class="panel cart-panel waiter-cart" aria-labelledby="cart-title">
                <div class="cart-heading">
                    <div>
                        <p class="eyebrow">TU SELECCIÓN</p>
                        <h3 id="cart-title" class="text-xl font-bold mt-1">{{ $pedido ? 'Productos por agregar' : 'Resumen del pedido' }}</h3>
                    </div>
                    <span class="cart-count" aria-label="Cantidad de productos" x-text="items.length">0</span>
                </div>
                <p class="text-sm text-gray-500 mt-2">Mesa {{ $mesa->numero }} · Revisa antes de confirmar.</p>

                <div class="empty-state cart-empty-state" x-show="items.length === 0">
                    <span class="cart-empty-icon"><x-icon name="orders" /></span>
                    <strong>Tu pedido empieza aquí</strong>
                    <p class="text-sm">Agrega productos desde la carta y aparecerán en este resumen.</p>
                </div>

                <div class="cart-items" aria-live="polite">
                    <template x-for="(item, index) in items" :key="item.key">
                        <div class="cart-line">
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-sm" x-text="item.nombre"></p>
                                <p class="text-xs text-gray-500 mt-1" x-show="item.componentes" x-text="item.componentes"></p>
                                <label class="cart-note-label">
                                    <span>Indicación para este producto</span>
                                    <input type="text" x-model="item.observaciones" maxlength="1000" class="search-input !text-sm !min-h-10 !py-2 mt-1" :aria-label="'Indicación para ' + item.nombre" placeholder="Ej. sin hielo">
                                </label>
                                <p class="cart-character-count"><span x-text="(item.observaciones || '').length"></span>/1000</p>
                            </div>
                            <div class="cart-line-actions">
                                <strong class="text-sm whitespace-nowrap" x-text="money(item.precio)"></strong>
                                <button type="button" class="quantity-button cart-remove-button" @click="items.splice(index, 1)" :aria-label="'Quitar ' + item.nombre">
                                    <span aria-hidden="true">×</span><span class="sr-only">Quitar</span>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="items.length > 0" x-cloak>
                    <label for="observaciones" class="text-sm font-semibold block mt-5 mb-2">Indicaciones para toda la mesa</label>
                    <textarea id="observaciones" rows="3" x-model="notes" maxlength="1000" class="search-input" placeholder="Ej. servir primero las bebidas"></textarea>
                    <p class="cart-character-count text-right"><span x-text="notes.length"></span>/1000</p>

                    <div class="cart-totals" x-show="previousTotal > 0">
                        <div><span>Cuenta anterior</span><span x-text="money(previousTotal)"></span></div>
                        <div><span>Productos nuevos</span><span x-text="money(total)"></span></div>
                    </div>
                    <div class="cart-grand-total">
                        <span>{{ $pedido ? 'Nuevo total de la mesa' : 'Total del pedido' }}</span>
                        <strong x-text="money(total + previousTotal)"></strong>
                    </div>

                    <p role="alert" class="order-submit-error" x-show="error && !blocked" x-text="error"></p>
                    <button type="submit" class="ui-button order-submit-button" :disabled="sending || blocked || $store.restaurant.expired">
                        <x-icon name="check" />
                        <span x-text="sending ? 'Enviando pedido…' : 'Enviar pedido al administrador'"></span>
                    </button>
                    <p class="order-submit-hint">Podrás seguir agregando productos mientras la cuenta esté activa.</p>
                </div>
            </aside>
        </div>

        <div class="waiter-mobile-summary" x-show="items.length > 0" x-cloak>
            <a href="#resumen-pedido" class="ui-button w-full !justify-between">
                <span>Revisar pedido (<span x-text="items.length"></span>)</span>
                <span x-text="money(total + previousTotal)"></span>
            </a>
        </div>
    </form>
</x-mesero-layout>
