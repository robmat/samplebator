#import "MainMenuVC.h"
#import "LoginVC.h"

@implementation MainMenuVC

- (void)viewDidLoad {
    [super viewDidLoad];
	[self hideBackBtn];
	self.navigationItem.titleView = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"title_image.png"]];
	self.navigationItem.hidesBackButton = YES;
}
- (void)viewWillAppear:(BOOL)animated {
	self.navigationController.navigationBarHidden = NO;
} 
- (void)loginAction:(id) sender {
	LoginVC* lvc = [[LoginVC alloc] init];
	[self.navigationController pushViewController:lvc animated:YES];
	[lvc release];
}
- (void)dealloc {
    [super dealloc];
}

@end
