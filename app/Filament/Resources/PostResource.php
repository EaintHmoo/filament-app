<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Category;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Post Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Create Post')
                ->description('This is description')
                ->collapsible()
                ->schema([
                    TextInput::make('title')->required(),
                    TextInput::make('slug'),
                    ColorPicker::make('color'),
                    MarkdownEditor::make('content'),
                    Select::make('category_id')
                            ->label('category')
                            ->options(Category::pluck('name','id')->toArray())
                ])->columnSpan(2),
               Group::make()
               ->schema([
                Section::make('Meta')
                ->collapsible()
                ->schema([
                    FileUpload::make('thumbnail')->disk('public')->directory('thumbnails')
                ]),
                Section::make('Others')
                ->schema([
                    TagsInput::make('tags'),
                    Checkbox::make('published'),
                ])
               ])   
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                ->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('title')
                ->toggleable()
                ->searchable()
                ->sortable(),
                TextColumn::make('slug')
                ->toggleable()
                ->searchable()
                ->sortable(),
                ColorColumn::make('color')
                ->toggleable(),
                TextColumn::make('content')
                ->toggleable(),
                TextColumn::make('category.name')
                ->toggleable()
                ->searchable()
                ->sortable(),
                TextColumn::make('tags')
                ->toggleable()
                ->searchable()
                ->sortable(),
                CheckboxColumn::make('published')
                ->toggleable(),
                ImageColumn::make('thumbnail')
                ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
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
}
