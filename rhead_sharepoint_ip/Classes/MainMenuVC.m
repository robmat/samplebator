#import "MainMenuVC.h"
#import "LoginVC.h"

@implementation MainMenuVC

- (void)viewDidLoad {
    [super viewDidLoad];
	[self hideBackBtn];
}
- (void) loginAction:(id) sender {
	LoginVC* lvc = [[LoginVC alloc] init];
	[self.navigationController pushViewController:lvc animated:YES];
	[lvc release];
}
- (void)dealloc {
    [super dealloc];
}

@end
