const defaultSelect = () => {

    const element = document.querySelector('.js-choice');
    const choices = new Choices(element, {
        
        maxItemCount: 4, 
        removeItemButton: true,
        renderSelectedChoices: 'auto',
        searchEnabled: true
    });


};

defaultSelect();