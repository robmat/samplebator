
//Copyright Applicable Ltd 2011

#import "CommonViewControllerBase.h"
#import "MainMenuViewController.h"

@implementation CommonViewControllerBase

- (void)viewDidLoad {
    [super viewDidLoad];
	
	UIBarButtonItem *anotherButton = [[UIBarButtonItem alloc] initWithImage:[UIImage imageNamed:@"home_btn.png"]
																	   style:UIBarButtonItemStyleBordered
																	 target:self 
																	 action:@selector(homeAction:)];
	self.navigationItem.rightBarButtonItem = anotherButton;
	[anotherButton release];
}
- (void) homeAction: (id) sender {
	[self.navigationController pushViewController:[[MainMenuViewController alloc] initWithNibName:nil bundle:nil] animated:YES];
}
- (void)dealloc {
    [super dealloc];
}

@end
