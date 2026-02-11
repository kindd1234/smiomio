<?php

namespace App\Filament\Resources\Posts;

use App\Filament\Resources\Posts\Pages\ManagePosts;
use App\Models\Account;
use App\Models\Page;
use App\Models\Post;
use App\Services\FacebookService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\View\View;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::PencilSquare;

    protected static ?string $recordTitleAttribute = 'Post';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->default_workspace_id !== null;
    }

    public static function form(Schema $schema): Schema
    {

        $accounts = Account::query()->whereHas('pages')->pluck('name', 'id');

        return $schema
            ->components([
                Select::make('account_id')
                    ->label('Account')
                    ->options(
                        $accounts
                    )
                    ->required()
                    ->columnSpanFull()
                    ->reactive(),

                Select::make('page_id')
                    ->label('Page')
                    ->required()
                    ->multiple()
                    ->columnSpanFull()
                    ->reactive()
                    ->options(function (callable $get) {
                        $accountId = (int) $get('account_id');

                        if (! $accountId) {
                            return [];
                        }

                        $pages = Page::with('childrens')
                            ->where('account_id', $accountId)
                            ->whereNull('parent_id')
                            ->get();

                        $options = [];

                        foreach ($pages as $page) {
                            if ($page->childrens->isNotEmpty()) {

                                $options[$page->remote_id] = $page->name;

                                foreach ($page->childrens as $child) {
                                    $options[$child->remote_id] = $page->name.' > '.$child->name;
                                }

                            } else {
                                $options[$page->remote_id] = $page->name;
                            }
                        }

                        return $options;
                    })
                    ->visible(
                        function (callable $get) {
                            return $get('account_id');
                        }
                    ),

                Select::make('type')
                    ->label('Type')
                    ->required()
                    ->columnSpanFull()
                    ->options([
                        'image' => 'Image',
                        'background' => 'Background preset',
                    ])
                    ->reactive()
                    ->visible(
                        function (callable $get) {
                            return $get('account_id') && $get('page_id');
                        }
                    )
                    ->default(''),

                TextInput::make('name')
                    ->label('Content')
                    ->columnSpanFull()
                    ->reactive()
                    ->visible(
                        function (callable $get) {
                            return $get('account_id') && $get('page_id') && $get('type');
                        }
                    )
                    ->default(''),

                FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->columnSpanFull()
                    ->label('Image')
                    ->reactive()
                    ->visible(fn ($get) => $get('type') === 'image'),

                Select::make('text_format_preset_id')
                    ->label('Background')
                    ->columnSpanFull()
                    ->options(
                        collect(self::getPresets())
                            ->pluck('background_description', 'preset_id')
                    )
                    ->visible(fn ($get) => $get('type') === 'background')
                    ->required(),

                Textarea::make('comment')
                    ->label('Comment')
                    ->reactive()
                    ->visible(
                        function (callable $get) {
                            return $get('account_id') && $get('page_id') && $get('type');
                        }
                    )
                    ->columnSpanFull(),
                Select::make('delay_comment')
                    ->label('Delay Comment (in seconds)')
                    ->columnSpanFull()
                    ->options([
                        0 => '0 seconds',
                        15 => '15 seconds',
                        30 => '30 seconds',
                        60 => '60 seconds (1 minute)',
                        120 => '120 seconds (2 minutes)',
                    ]
                    )
                    ->default(0)
                    ->visible(fn ($get) => ! empty(trim($get('comment') ?? '')))
                    ->required(),
                DateTimePicker::make('scheduled_at')
                    ->native(false)
                    ->minutesStep(1)
                    ->seconds(false)
                    ->reactive()
                    ->visible(
                        function (callable $get) {
                            return $get('account_id') && $get('page_id') && $get('type');
                        }
                    )
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('page_id')
                    ->numeric(),
                TextEntry::make('name'),
                ImageEntry::make('image'),
                TextEntry::make('visibility'),
                TextEntry::make('scheduled_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('published_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    private static function getPresets()
    {

        return [
            [
                'preset_id' => '1038184293978413',
                'background_description' => 'Light grey ',
                'default_thumbnail' => 'https://scontent-ams2-1.xx.fbcdn.net/v/t39.10873-6/386362550_1038184297311746_2223192557952669240_n.jpg',
            ],
            [
                'preset_id' => '340531735020539',
                'background_description' => 'Grey ',
                'default_thumbnail' => 'https://scontent-ams2-1.xx.fbcdn.net/v/t39.10873-6/370285786_340531745020538_7069811657427646491_n.jpg',
            ],
            [
                'preset_id' => '334764089044169',
                'background_description' => 'Black ',
                'default_thumbnail' => 'https://scontent-ams2-1.xx.fbcdn.net/v/t39.10873-6/370135650_334764095710835_4222116435565970166_n.jpg',
            ],
            [
                'preset_id' => '3121716424802062',
                'background_description' => 'Pink ',
                'default_thumbnail' => 'https://scontent-ams2-1.xx.fbcdn.net/v/t39.10873-6/386188445_3121716438135394_4021166882493213264_n.jpg',
            ],
            [
                'preset_id' => '3625555494348449',
                'background_description' => 'Red ',
                'default_thumbnail' => 'https://scontent-ams2-1.xx.fbcdn.net/v/t39.10873-6/370044765_3625555507681781_7882834888241462920_n.jpg',
            ],
            [
                'preset_id' => '841428021039542',
                'background_description' => 'Dark red ',
                'default_thumbnail' => 'https://scontent-ams2-1.xx.fbcdn.net/v/t39.10873-6/386755620_841428031039541_1502067022190545607_n.jpg',
            ],
            [
                'preset_id' => '287628994046344',
                'background_description' => 'Crimson ',
                'default_thumbnail' => 'https://scontent-ams2-1.xx.fbcdn.net/v/t39.10873-6/386261289_287628997379677_6507060816728119831_n.jpg',
            ],
            [
                'preset_id' => '866176818274367',
                'background_description' => 'Beige ',
                'default_thumbnail' => 'https://scontent-ams2-1.xx.fbcdn.net/v/t39.10873-6/386396062_866176828274366_225020822492365166_n.jpg',
            ],
            [
                'preset_id' => '653263790240452',
                'background_description' => 'Orange ',
                'default_thumbnail' => 'https://scontent-ams2-1.xx.fbcdn.net/v/t39.10873-6/386392711_653263800240451_6910000120771030606_n.jpg',
            ],
            [
                'preset_id' => '618237107054113',
                'background_description' => 'Brown ',
                'default_thumbnail' => 'https://scontent-ams2-1.xx.fbcdn.net/v/t39.10873-6/386332480_618237120387445_715847918794715752_n.jpg',
            ],
            [
                'preset_id' => '2046306532386635',
                'background_description' => 'Light yellow ',
                'default_thumbnail' => 'https://scontent-ams2-1.xx.fbcdn.net/v/t39.10873-6/386695648_2046306542386634_2976510271189555207_n.jpg',
            ],
            [
                'preset_id' => '184083004658498',
                'background_description' => 'Yellow ',
                'default_thumbnail' => 'https://scontent-ams2-1.xx.fbcdn.net/v/t39.10873-6/386718652_184083007991831_273487719870199963_n.jpg',
            ],
            [
                'preset_id' => '696971568609418',
                'background_description' => 'Dark yellow ',
                'default_thumbnail' => 'https://scontent-ams2-1.xx.fbcdn.net/v/t39.10873-6/386383422_696971575276084_4288754063570553455_n.jpg',
            ],
            [
                'preset_id' => '861160898741935',
                'background_description' => 'Light green ',
                'default_thumbnail' => 'https://scontent-ams2-1.xx.fbcdn.net/v/t39.10873-6/386460117_861160905408601_1974676806410940525_n.jpg',
            ],
            [
                'preset_id' => '680142694061655',
                'background_description' => 'Olive green ',
                'default_thumbnail' => 'https://scontent-ams2-1.xx.fbcdn.net/v/t39.10873-6/386388962_680142700728321_395675106290850537_n.jpg',
            ],
            [
                'preset_id' => '345064321202371',
                'background_description' => 'Green ',
                'default_thumbnail' => 'https://scontent-ams2-1.xx.fbcdn.net/v/t39.10873-6/387029400_345064324535704_540871292518625277_n.jpg',
            ],
            [
                'preset_id' => '137309512798730',
                'background_description' => 'Light blue ',
                'default_thumbnail' => 'https://scontent-ams2-1.xx.fbcdn.net/v/t39.10873-6/386465313_137309522798729_5433514653022057236_n.jpg',
            ],
            [
                'preset_id' => '685611216963500',
                'background_description' => 'Teal ',
                'default_thumbnail' => 'https://scontent-ams2-1.xx.fbcdn.net/v/t39.10873-6/386192233_685611223630166_607337326081316706_n.jpg',
            ],
        ];

    }

    public static function table(Table $table): Table
    {
        $accounts = Account::where('user_id', request()->user()->id)->get()->pluck('id')->toArray();

        $pageIds = Page::whereIn('account_id', $accounts)->get()->pluck('remote_id')->toArray();

        return $table
            ->recordTitleAttribute('Post')
            ->modifyQueryUsing(function ($query) use ($pageIds) {
                return $query->whereIn('page_id', $pageIds)->orderBy('id', 'DESC');
            })
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('page.name')
                    ->label('Page')
                    ->formatStateUsing(function ($state, $record) {
                        return "<a href='https://facebook.com/{$record->page->remote_id}' target='_blank'>{$state}</a>";
                    })
                    ->sortable()
                    ->html(),
                TextColumn::make('name')
                    ->label('Content')
                    ->limit(24)
                    ->searchable(),
                TextColumn::make('type'),
                TextColumn::make('visibility')
                    ->searchable(),

                \Filament\Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'published',
                        'danger' => 'failed',
                        'warning' => 'queued',
                        'warning' => 'archived',
                        'primary' => 'scheduled',
                    ])
                    ->sortable(),
                TextColumn::make('scheduled_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->multiple()
                    ->searchable()
                    ->options([
                        'scheduled' => 'Scheduled',
                        'queued' => 'Queued',
                        'published' => 'Published',
                        'failed' => 'Failed',
                        'archived' => 'Archived',
                    ])
                    ->default(['scheduled', 'queued', 'published', 'failed']),
                SelectFilter::make('account_id')
                    ->label('Account')
                    ->multiple()
                    ->searchable()
                    ->options(
                        Account::query()
                            ->pluck('name', 'id')
                            ->toArray()
                    ),
                SelectFilter::make('page_id')
                    ->label('Page')
                    ->multiple()
                    ->searchable()
                    ->options(function () {
                        $pages = Page::with('childrens')->whereNull('parent_id')->get();

                        $options = [];
                        foreach ($pages as $page) {
                            if ($page->childrens->isNotEmpty()) {
                                foreach ($page->childrens as $child) {
                                    $options[$child->remote_id] = $page->name.' > '.$child->name;
                                }
                            } else {
                                $options[$page->remote_id] = $page->name;
                            }
                        }

                        return $options;
                    }),
            ])
            ->recordActions([
                ViewAction::make('visit')
                    ->label('Visit')
                    ->url(fn ($record) => "https://www.facebook.com/{$record->remote_id}")
                    ->visible(fn ($record) => ! empty($record->remote_id))
                    ->openUrlInNewTab(),
                Action::make('details')
                    ->action(fn (Post $record) => $record->advance())
                    ->modalContent(fn (Post $record): View => view(
                        'filament.partials.post-details',
                        [
                            'post' => $record,
                            'details' => (new FacebookService)->getPostInsights($record),
                        ],
                    ))
                    ->visible(fn ($record) => ! empty($record->remote_id))
                    ->modalSubmitAction(false),

                DeleteAction::make(),
            ])
            ->poll('10s')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
                Action::make('archive')
                    ->label('Archive')
                    ->accessSelectedRecords()
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            $record->update(['status' => 'archived']);
                        }
                    })
                    ->requiresConfirmation()
                    ->color('danger'),  //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePosts::route('/'),
        ];
    }
}
