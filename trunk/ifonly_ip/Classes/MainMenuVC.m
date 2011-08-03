#import "MainMenuVC.h"
#import "ChooseVideoSourceVC.h"

@implementation MainMenuVC

- (void)viewDidLoad {
    [super viewDidLoad];
}

- (IBAction) recordMovieAction: (id) sender {
	ChooseVideoSourceVC* cvvc = [[ChooseVideoSourceVC alloc] init];
	[self.navigationController pushViewController:cvvc animated:YES];
	[cvvc release];
}

- (void)dealloc {
    [super dealloc];
}

@end
