<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make("Main Content")->schema([
                    Forms\Components\TextInput::make('title')->live()->required()->maxLength(150)
                        ->afterStateUpdated(function (string $operation, $state, Forms\Set $set){
                            if ($operation === 'edit')
                            {
                                return;
                            }
                            $set('slug', Str::slug($state));
                        }),
                    Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                    Forms\Components\RichEditor::make('content')->required()
                        ->fileAttachmentsDirectory('posts/images')->columnSpanFull(),

                ])->columns(2),
                Forms\Components\Section::make("Meta")->schema([
                    Forms\Components\FileUpload::make('image')->image()->directory('posts/thumbnails'),
                    Forms\Components\DateTimePicker::make('published_at')->nullable(),
                    Forms\Components\Checkbox::make('featured'),
                    Forms\Components\Select::make('user_id')->relationship('author', 'name')->searchable(),
                    Forms\Components\Select::make('categories')->multiple()
                        ->relationship('categories', 'title')->searchable(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('slug')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('author.name')->sortable()->searchable(),
                Tables\Columns\CheckboxColumn::make('featured'),
                Tables\Columns\TextColumn::make('published_at')->date('Y-m-d')->sortable()->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CommentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
