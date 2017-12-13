<!-- Begin Bitbucket Slide -->
<div data-template="bitbucket">
    <div class="slide slide-bitbucket">
        <div class="slide-inner">
            <div class="content">
                <h1 data-placeholder="title" data-placeholder-renders="striptags,ellipsis:150"></h1>
                <div class="table">
                    <div class="table-head">
                        <span class="table-column table-column-name">Naam</span>
                        <span class="table-column table-column-commits">Commits</span>
                        <span class="table-column table-column-rate">Punten</span>
                    </div>
                    <div class="table-body" data-placeholder="items">
                        <div class="table-row" data-template="item">
                            <span class="table-column table-column-name">
                                <span data-placeholder="idx"></span>. <span data-placeholder="name"></span>
                            </span>
                            <span class="table-column table-column-commits">
                                <span class="rate-success">
                                    <span class="icon"></span>
                                    <span data-placeholder="success"></span>
                                </span>
                                 /
                                <span class="rate-failed">
                                    <span data-placeholder="failed"></span>
                                    <span class="icon"></span>
                                </span>
                            </span>
                            <span class="table-column table-column-score">
                                <span data-placeholder="score"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Bitbucket Slide -->